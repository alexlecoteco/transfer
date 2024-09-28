<?php

namespace HyperfTest\Cases;

use HyperfTest\AbstractTest;

class TransferTest extends AbstractTest
{

    public function testRequestRestrictions(): void
    {
        $response = $this->post('/transfer', []);
        //TODO Testar todas as restrições da classe TransferRequest
    }

    public function testRequestSuccess(): void
    {
        //TODO validar a resposta do JSON
        //TODO validar se bate com o dado do banco
    }

    public function testValidateErrorCacheOnRedis(): void
    {
        //TODO validar erros que podem ser armazenados nos redis por falha dos serviços externos
    }

    public function testValidateNotificationSent(): void
    {
        //TODO validar se não foi criado um cache de erro de notificacao no redis
    }
}
