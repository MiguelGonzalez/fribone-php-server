<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../database_inflater.php';

class SupermercadoTest extends PHPUnit_Framework_TestCase {
	private $CI;

	private static $dataBase_inflater;

    public static function setUpBeforeClass() {
    	try {
	        self::$dataBase_inflater = new DataBase_inflater();
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

    public static function tearDownAfterClass() {
    	try {
        	self::$dataBase_inflater = NULL;
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

    public function __construct() {
    	parent::__construct();

    	$this->CI = &get_instance();
    	$this->CI->load->model('supermercado');
    }

	public function setUp() {
		try {
			self::$dataBase_inflater->create();
		} catch(Exception $ex) {
			self::fail($ex);
		}
    }

    public function tearDown() {
    	try {
    		self::$dataBase_inflater->destroy();
    	} catch(Exception $ex) {
			self::fail($ex);
		}
    }

	public function testSupermercados() {
		$supermercados = $this->CI->supermercado->get_supermercados();
		$this->assertNull($supermercados);

        $res = $this->CI->supermercado->add_supermercado('mercadona');
        $this->assertTrue($res !== NULL);

        $supermercados = $this->CI->supermercado->get_supermercados();
        $this->assertTrue($supermercados !== NULL);
	}

    public function testProductoSupermercado() {
        $res = $this->CI->supermercado->add_supermercado('mercadona');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Agua',
            'codigo_barras' => '123006456046',
            'descripcion' => 'Botella de agua de 1L del mercadona',
            'precio' => 0.87,
            'unidades' => 1
        );
        $res = $this->CI->supermercado->add_supermercado_producto($idSupermercado, $datosProducto);
        $this->assertTrue($res !== NULL);
    }
}