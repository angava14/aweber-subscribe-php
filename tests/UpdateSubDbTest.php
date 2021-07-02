<?php

    class UpdateSubDbTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        public function testUpdateSubDBNull(){
            exec('php updateToken.php');
            $this->expectException(InvalidArgumentException::class);
            $this->op->updateSubDB(null);
        }
    }

?>