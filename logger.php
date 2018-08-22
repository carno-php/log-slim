<?php
/**
 * Provide base logger if have no "log" package
 * User: moyo
 * Date: 2018/8/17
 * Time: 2:39 PM
 */

if (!class_exists('Carno\\Log\\Logger') && !function_exists('logger')) {
    /**
     * @param string $scene
     * @return \Psr\Log\LoggerInterface
     */
    function logger(string $scene = 'default') : \Psr\Log\LoggerInterface
    {
        static $loggers = [];

        if (isset($loggers[$scene])) {
            return $loggers[$scene];
        }

        return $loggers[$scene] = new class ($scene) extends \Psr\Log\AbstractLogger {
            /**
             * @var string
             */
            private $scene = 'log';

            /**
             * anonymous constructor.
             * @param string $scene
             */
            public function __construct(string $scene)
            {
                $this->scene = $scene;
            }

            /**
             * @param mixed $level
             * @param string $message
             * @param array $context
             */
            public function log($level, $message, array $context = []) : void
            {
                $arguments = '';

                array_walk($context, function ($item, $key) use (&$arguments) {
                    $arguments .= sprintf(
                        '%s=%s',
                        $key,
                        is_array($item)
                            ? json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                            : (string) $item
                    ) . ',';
                });

                echo sprintf(
                    '[%s] (%s) %s -- %s :: [%s]',
                    date('H:i:s'),
                    $this->scene,
                    strtoupper($level),
                    $message,
                    $arguments
                ), PHP_EOL;
            }
        };
    }
}
