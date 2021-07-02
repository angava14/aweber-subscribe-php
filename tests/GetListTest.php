<?php

    class GetListTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        
        public function testGetList(){
            exec('php updateToken.php');
            $this->assertSame(6075602 , $this->op->getList('1784570'));
        }

        public function testGetListNullClientID(){
            exec('php updateToken.php');
            $this->expectException(InvalidArgumentException::class);
            $this->op->getList(null);
        }

        public function testGetListNotNumericClientID(){
            exec('php updateToken.php');
            $this->expectException(InvalidArgumentException::class);
            $this->op->getList('Ejemplo');
        }
    }

?>