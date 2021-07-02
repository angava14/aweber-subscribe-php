<?php

    class AddSubDbTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        public function testAddSubDBNull(){
            exec('php updateToken.php');
            $this->expectException(InvalidArgumentException::class);
            $this->op->addSubDB(null);
        }
    }

?>