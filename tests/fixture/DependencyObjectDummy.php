<?php
namespace trogon\otuspdf\test\fixture;

use trogon\otuspdf\base\DependencyObject;

final class DependencyObjectDummy extends DependencyObject
{
    public $winstonDumpValue;

    public function getRonald() { return 'test ronald'; }
    public function setWinston($value) { $this->winstonDumpValue = $value; }
}
