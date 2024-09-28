<?php

namespace HyperfTest\Cases;

use HyperfTest\AbstractTest;

class TransferTest extends AbstractTest
{

    public function testRequestRestrictions()
    {
        $response = $this->post('/transfer', []);
    }

    public function testRequestSuccess()
    {
        //TODO validar a resposta do JSON
        //TODO validar se bate com o dado do banco
    }

    public function testValidateErrorCacheOnRedis()
    {
        //TODO validar erros que podem ser armazenados nos redis por falha dos serviços externos
    }

    public function testValidateNotificationSent()
    {
        //TODO validar se não foi criado um cache de erro de notificacao no redis
    }
}
