<?php

require_once 'core/config/dbconfig.inc.php';

class WebAddress
{
    private const SHORTSTUB = ''; // adds to front of flatfile redirect


    public $short;
    public $long;

    public function __construct(string $long, $short = '')
    {
        $this->long = $long;
        $this->short = $short;
    }

    public function notUnique(): bool
    {
        $user = USER;
        $password = SECRET;
        $database = NAMEOFDATABASE;
        $host = '127.0.0.1'; // localhost, even in production, todo - confirm once deployed
        $queryResult = [];
        try {
            $db = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
            $queryResult = $db->query("select short from links where short = '" . $this->short . "'")->fetchAll(PDO::FETCH_ASSOC);
            $numberOfLinksFoundInDB = count($queryResult);
        } catch (PDOException $e) {
            print "Whoa, error!: " . $e->getMessage() . "<br/>";
            //todo this belongs as a log message
        }

        $urlExists = (bool)$numberOfLinksFoundInDB;

        return $urlExists; 
    }

    public function shortify(): void
    {
        // trying to make it memorable
        // 576,000 permutations possible before a repeat needed
        $this->short = self::SHORTSTUB;
        $this->short .=
            $this->consonant() .
            $this->vowel() .
            $this->consonant() .
            $this->consonant() .
            $this->vowel() .
            $this->consonant() .
            $this->digit() .
            $this->digit();
    }

    public function getLong(): string
    {
        return $this->long;
    }

    public function getShort(): string
    {
        return $this->short;
    }

    private function vowel(): string
    {
        $a_y = 'aeiouy';
        return $a_y[random_int(0, 5)];
    }

    private function consonant(): string
    {
        $b_z = 'bcdfghjklmnpqrstvwxz';
        return $b_z[random_int(0, 19)];
    }

    private function digit(): string
    {
        $nums = '1234567890';
        return $nums[random_int(0, 9)];
    }
}
