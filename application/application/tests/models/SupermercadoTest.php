<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class SupermercadoTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('supermercado_model');
    }

	public function testSupermercados() {
		$supermercados = $this->CI->supermercado_model->get_supermercados();
		$this->assertEquals(count($supermercados), 0);

        $res = $this->CI->supermercado_model->crear_supermercado('mercadona');
        $this->assertTrue($res !== NULL);

        $supermercados = $this->CI->supermercado_model->get_supermercados();
        $this->assertEquals(count($supermercados), 1);
	}

    public function testProductoSupermercado() {
        $res = $this->CI->supermercado_model->crear_supermercado('mercadona_dos');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Agua',
            'codigo_barras' => '123006456046',
            'descripcion' => 'Botella de agua de 1L del mercadona',
            'precio' => 0.87,
            'unidades' => 1
        );
        $res = $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);
        $this->assertTrue($res !== NULL);
    }

    public function testGetProductosSupermercado() {
        $res = $this->CI->supermercado_model->get_supermercado_productos(-1);
        $this->assertNull($res);

        $res = $this->CI->supermercado_model->crear_supermercado('mercadona_tres');
        $idSupermercado = $res['supermercado_id'];

        $res = $this->CI->supermercado_model->get_supermercado_productos($idSupermercado);
        $this->assertEquals(0, count($res));

        $datosProducto = array(
            'titulo' => 'Agua',
            'codigo_barras' => '123006456046',
            'descripcion' => 'Botella de agua de 1L del mercadona',
            'precio' => 0.87,
            'unidades' => 1
        );
        $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);

        $res = $this->CI->supermercado_model->get_supermercado_productos($idSupermercado);
        $this->assertEquals(1, count($res));
        $this->assertEquals('Agua',$res[0]->titulo);
    }

    public function testGetProductoSupermercado() {
        $res = $this->CI->supermercado_model->crear_supermercado('mercadona_cuatro');
        $idSupermercado = $res['supermercado_id'];

        echo " Id $idSupermercado ";

        $res = $this->CI->supermercado_model->get_supermercado_productos($idSupermercado);

        $datosProducto = array(
            'titulo' => 'Agua',
            'codigo_barras' => '123006456046',
            'descripcion' => 'Botella de agua de 1L del mercadona',
            'precio' => 0.87,
            'unidades' => 1
        );
        $res = $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);
        $idProducto = $res['id_producto'];

        $producto = $this->CI->supermercado_model->get_producto_supermercado($idProducto);
        $this->assertEquals('Agua', $producto->titulo);
        $this->assertEquals('123006456046', $producto->codigo_barras);
        $this->assertEquals(0.87, $producto->precio);
    }
}