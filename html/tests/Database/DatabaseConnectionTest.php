<?php

use PHPUnit\Framework\TestCase;
use App\Database\DatabaseConnection;


class DatabaseConnectionTest extends TestCase
{
    private $db;
    private $pdo;
    private $stmt;

    public static function setUpBeforeClass(): void
    {
        // Configuração inicial do banco de dados de teste.
        //Insira os dados alteres os parametros conforme configuração do seu banco de teste
        DatabaseConnection::config('driver', 'localhost', 'testdb', 'testuser', 'testpass', '5432');
    }

    protected function setUp(): void
    {
        // Mocking PDO e PDOStatement
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        // Injetando o mock da conexão PDO na classe DatabaseConnection
        $this->db = new DatabaseConnection($this->pdo);
    }

    public function testFetchAll()
    {
        $sql = "SELECT * FROM test_table";
        $params = [];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with($params);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([['id' => 1, 'name' => 'Test']]);

        $result = $this->db->fetchAll($sql, $params);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Test', $result[0]['name']);
    }

    public function testFetch()
    {
        $sql = "SELECT * FROM test_table WHERE id = ?";
        $params = [1];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with($params);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['id' => 1, 'name' => 'Test']);

        $result = $this->db->fetch($sql, $params);

        $this->assertIsArray($result);
        $this->assertEquals('Test', $result['name']);
    }

    public function testFormatJson()
    {
        $data = ['name' => 'Test'];
        $expectedJson = json_encode($data);

        // Using reflection to access private method
        $reflection = new \ReflectionClass(DatabaseConnection::class);
        $method = $reflection->getMethod('formatJson');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->db, [$data]);

        $this->assertEquals($expectedJson, $result);
    }
}
