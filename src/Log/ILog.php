<?php

namespace IotSpace\Log;

interface ILog
{

    public function error($message, $context = []);

    public function info($message, $context = []);
}