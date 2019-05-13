<?php namespace insma\otuspdf\meta;

class DocumentInfo extends \insma\otuspdf\base\BaseObject
{
    public $title;
    public $author;
    public $subject;
    public $keywords;
    private $creationDate;
    private $modificationDate;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function getCreator()
    {
        return 'Otus PDF by Insma';
    }

    public function getProducer()
    {
        return 'Otus PDF by Insma';
    }

    public function getCreationDate()
    {
        if (empty($this->creationDate)) {
            $this->generateDateInfo();
        }
        return $this->creationDate;
    }

    public function getModificationDate()
    {
        if (empty($this->modificationDate)) {
            $this->generateDateInfo();
        }
        return $this->modificationDate;
    }

    private function generateDateInfo()
    {
        $dateTime = \date('YmdHis');
        $timezone = \str_replace(':', '\'', \date('P'));
        $this->modificationDate = $this->creationDate = "D:{$dateTime}{$timezone}'";
    }
}
