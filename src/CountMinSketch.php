<?php
namespace Imoca\CountMinSketch;

class CountMinSketch {
    /** @var int  */
    private $width = 0;

    /** @var int  */
    private $heigth = 0;

    /** @var string  */
    private $filename = '';

    /** @var resource  */
    private $store;

    public function __construct(int $h, int $w, string $filename)
    {
        $this->width = $w;
        $this->heigth = $h;
        $this->filename = $filename;
        if (!file_exists($filename)) {
            touch($filename);
        }
        if (!($handle = @fopen($filename, 'r+'))) {
            throw new \Exception("Cannot open file.");
        }
        if (filesize($filename) == 0) {
            for ($i = 0; $i < $h * $w; $i++) {
                $binarystring = pack ("i", 0);
                fwrite($handle, $binarystring);
            }
        }
        $this->store = $handle;
    }

    public function __destruct()
    {
        fclose($this->store);
    }

    private function _position(string $key, int $index): int
    {
        $r = (crc32(sha1($key . $index)))% $this->width;

        return ($r < 0) ? $r + $this->width : $r;
    }

    public function record(string $key): void
    {
        for ($h = 0; $h < $this->heigth; $h++) { 
           $position = $this->_position($key, $h);
           $v = $this->readAt($h, $position);
           $this->writeAt($h, $position, $v + 1);
        }
    }

    public function count(string $key) : int
    {
        $min = PHP_INT_MAX;
        for ($h = 0; $h < $this->heigth; $h++) { 
            $position = $this->_position($key, $h);
            $v = $this->readAt($h, $position);
            if ($v < $min) {
                $min = $v;
            }
        }

        return $min;
    }

    private function readAt(int $h, int $position): int
    {
        $seek = 4 * ($position + $h * $this->width);
        fseek($this->store, $seek);
        $contents = fread($this->store, 4);

        return unpack ("i", $contents)[1];
    }

    private function writeAt(int $h, int $position, int $value): void
    {
        $seek = 4 * ($position + $h * $this->width);
        fseek($this->store, $seek);
        fwrite($this->store, pack ("i", $value));
    }

    public function __toString()
    {
        $lines = [];
        for ($j = 0; $j < $this->heigth; $j++) {
            $lines[$j] = '';
        }
        for ($i = 0; $i < $this->width; $i++) {
            $char_size = 0;
            $items = []; 
            for ($j = 0; $j < $this->heigth; $j++) { 
                $c = "" . $this->readAt($j, $i);
                $size = strlen($c);
                if ($size > $char_size) {
                    $char_size = $size;
                }
                $items[$j] = ["char" => $c, "size" => $size]; 
            }
            foreach($items as $key => $item) {
                $lines[$key] .= ' ' . $item['char'] . str_repeat(" ", $char_size - $item["size"]); 
            }
        }

        return implode(PHP_EOL, $lines);
    }
}
