<?php

namespace Tests\Common;

use PHPUnit\Framework\TestCase;
use App\Common\Environment;

class EnvironmentTest extends TestCase
{
    private $envFilePath;

    protected function setUp(): void
    {
        // Diretório temporário para o teste
        $this->envFilePath = sys_get_temp_dir() . '/.env';
        
        // Conteúdo do arquivo .env para o teste
        $envContent = "TEST_VAR1=value1\nTEST_VAR2=value2\n";
        
        // Cria um arquivo .env temporário para o teste
        file_put_contents($this->envFilePath, $envContent);
    }

    protected function tearDown(): void
    {
        // Remove o arquivo .env temporário após o teste
        if (file_exists($this->envFilePath)) {
            unlink($this->envFilePath);
        }
    }

    public function testLoadEnvFile()
    {
        // Carrega o arquivo .env temporário
        Environment::load(sys_get_temp_dir());

        // Verifica se as variáveis de ambiente foram definidas corretamente
        $this->assertEquals('value1', getenv('TEST_VAR1'));
        $this->assertEquals('value2', getenv('TEST_VAR2'));
    }

    public function testLoadEnvFileNotExists()
    {
        // Remove o arquivo .env temporário
        unlink($this->envFilePath);

        // Tenta carregar um arquivo .env que não existe
        $result = Environment::load(sys_get_temp_dir());

        // Verifica se o resultado é false
        $this->assertFalse($result);
    }
}
