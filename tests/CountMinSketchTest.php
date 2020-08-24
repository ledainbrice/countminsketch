<?php
require 'vendor/autoload.php';

use Imoca\CountMinSketch\CountMinSketch;
use PHPUnit\Framework\TestCase;

/**
 * Imoca\Imoca\CountMinSketch\CountMinSketch TestCase
 */
class CountMinSketchTest extends TestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->filename = "tmp/store.cms";
        @unlink($this->filename);
        $this->sports = [
            "Marche" => 6,
            "Marathon" => 8,
            "Course de fond" => 14,
            "Demi-fond" => 9,
            "Course d'obstacles" => 18,
            "Sprint" => 6,
            "Course de relais" => 16,
            "Lancer du disque" => 16,
            "Lancer du javelot" => 17,
            "Lancer du marteau" => 17,
            "Lancer du poids" => 15, 
            "Saut en longueur" => 16,
            "Saut en hauteur" => 15,
            "Saut à la perche" => 28,
            "Triple saut" => 11,
            "Décathlon" => 10,
            "Heptathlon" => 10
        ];
        $this->cms = new CountMinSketch(4, 20, $this->filename);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        @unlink($this->filename);

        parent::tearDown();
    }
    /**
     * Test method
     *
     * @return void
     */
    public function testSports()
    {
        foreach($this->sports as $sport => $_eval) {
            $count = strlen($sport);
            for ($i = 0; $i < $count; $i++) { 
                $this->cms->record($sport);
            }
        }

        foreach($this->sports as $sport => $eval) {
            $this->assertEquals($eval, $this->cms->count($sport));
        }
    }
}