<?php

    class GetClientTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }

        public function testGetClient(){
            exec('php updateToken.php');
            $this->assertSame(1784570 , $this->op->getClient());
        }

    }
?>
