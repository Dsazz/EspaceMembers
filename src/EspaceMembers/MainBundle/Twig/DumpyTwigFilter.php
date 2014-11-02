<?php
namespace EspaceMembers\MainBundle\Twig;

use Symfony\Component\Yaml\Dumper as YamlDumper;

class DumpyTwigFilter extends \Twig_Extension
{
    /** @const INLINE : default value for the inline parameter of the YAML dumper aka the expanding-level */
    const INLINE = 3;

    /** @const DEPTH : maximum recursion depth. Will set the INLINE parameter so it stays easily readable */
    const MAX_DEPTH = 1;

    const METHOD_IS_ACCESSOR_REGEX = '#^(get|has|is)#i';

    public function getFilters()
    {
        $optionsForRaw = array('is_safe' => array('all')); // allows raw dumping (otherwise <pre> is encoded)

        return array(
            'pre'   => new \Twig_Filter_Method($this, 'pre', $optionsForRaw),
            'dump'  => new \Twig_Filter_Method($this, 'preDump', $optionsForRaw),
            'ydump' => new \Twig_Filter_Method($this, 'preYamlDump', $optionsForRaw),
            'dumpy' => new \Twig_Filter_Method($this, 'preYamlDump', $optionsForRaw),
        );
    }

    public function pre($stringable)
    {
        return "<pre>" . (string) $stringable . "</pre>";
    }
    public function preDump($values)
    {
        return $this->pre(print_r($values, 1));
    }

    public function preYamlDump($values, $depth = self::MAX_DEPTH)
    {
        return $this->pre($this->yamlDump($values, $depth));
    }

    /**
     * Encodes as YAML the passed $input
     *
     * @param $input
     * @param  int   $inline
     * @return mixed
     */
    public function encode($input, $inline = self::INLINE)
    {
        static $dumper;

        if (null === $dumper) {
            $dumper = new YamlDumper();
        }

        return $dumper->dump($input, $inline);
    }

    /**
     * Returns a templating-helper dump of depth-sanitized var as yaml string
     *
     * @param  mixed  $value What to dump
     * @param  int    $depth Recursion max depth
     * @return string
     */
    public function yamlDump($value, $depth = self::MAX_DEPTH)
    {
        return $this->encode($this->sanitize($value, $depth), $depth * 2 + 1);
    }

    /**
     * Will sanitize mixed vars by capping recursion
     * and format them in a designer-friendly way by displaying objects' methods stubs
     * A bit dirty as this should be in another Class, but hey
     *
     * @param $value
     * @param  int          $maxRecursionDepth The maximum depth of recursion
     * @param  int          $recursionDepth    The depth of recursion (used internally)
     * @return array|string
     */
    public function sanitize($value, $maxRecursionDepth = self::MAX_DEPTH, $recursionDepth = 0)
    {
        if (is_resource($value)) {
            return 'Resource';
        }

        if (is_array($value)) {
            return $this->sanitizeIterateable ($value, $maxRecursionDepth, $recursionDepth);
        }

        if (is_object($value)) {
            $class = new \ReflectionClass(get_class($value));

            if ($recursionDepth >= $maxRecursionDepth) { // We're full, just scrap the vital data
                $classInfo = $class->getName();
                if ($class->hasMethod('getId')) {
                    $getIdMethod = $class->getMethod('getId');
                    if (!$getIdMethod->getNumberOfRequiredParameters()) {
                        $id = $getIdMethod->invoke($value);
                        $classInfo .= ($id !== null) ? ' #' . $id : ' (no id)';
                    }
                }
                if ($class->hasMethod('__toString')) { // robustness, we don't care about perf
                    try {
                        $classInfo .= ' ' . (string) $value;
                    } catch (\Exception $e) {
                        $classInfo .= '(string) casting throws Exception, please report or fix';
                    }
                }
                if ($value instanceof \DateTime) {
                    $classInfo .= ' : ' . $value->format('Y-m-d H:i:s');
                }

                return $classInfo;

            } else { // Get all accessors and their values
                $data = array();
                $data['class'] = '<span title="' . $class->getName(). '">' . $class->getShortName() . '</span>';
                if ($class->isIterateable()) {
                    $data['iterateable'] = $this->sanitizeIterateable ($value, $maxRecursionDepth, $recursionDepth);
                } else {
                    $data['accessors'] = array();
                    foreach ($class->getMethods() as $method) {
                        if ($method->isPublic() && preg_match(self::METHOD_IS_ACCESSOR_REGEX, $method->getName())) {
                            $methodInfo = $method->getName() . '(';
                            foreach ($method->getParameters() as $parameter) {
                                $methodInfo .= '$' . $parameter->getName() . ', ';
                            }
                            $methodInfo = ($method->getNumberOfParameters() ? substr($methodInfo, 0, -2) : $methodInfo) . ')';
                            if (!$method->getNumberOfRequiredParameters()) { // Get the value, we don't need params
                                $methodValue = $method->invoke($value);
                                $data['accessors'][$methodInfo] = $this->sanitize($methodValue, $maxRecursionDepth, $recursionDepth + 1);
                            } else { // Get only method name and its params
                                $data['accessors'][] = $methodInfo;
                            }
                        }
                    }

                }

                return $data;
            }
        }

        return $value;
    }

    public function sanitizeIterateable($value, $maxRecursionDepth = self::MAX_DEPTH, $recursionDepth = 0)
    {
        if ($recursionDepth < $maxRecursionDepth) {
            $r = array ();
            foreach ($value as $k => $v) {
                $r[$k] = $this->sanitize($v, $maxRecursionDepth, $recursionDepth + 1);
            }

            return $r;
        } else {
            $c = count($value); $t = gettype($value);

            return $c ? "$t of $c" : "empty $t";
        }
    }

    public function getName()
    {
        return 'dumpy_twig_filter';
    }
}
