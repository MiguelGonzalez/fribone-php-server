<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Supermercado_libraryTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->library('supermercado_library');
    }

    public function testGetEmptySupermercados() {
        $res = $this->CI->supermercado_library->get_supermercados();
        $this->assertEquals(count($res), 0);

        $res = $this->CI->supermercado_library->get_supermercado(1);
        $this->assertNull($res);
    }

    public function testCreateSupermercado() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $this->assertTrue($res !== NULL);

        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $this->assertNull($res);
    }

    public function testCreateGetSupermercado() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $idSupermercado = $res['supermercado_id'];

        $res = $this->CI->supermercado_library->get_supermercados();
        $this->assertEquals(count($res), 1);

        $res = $this->CI->supermercado_library->get_supermercado($idSupermercado);
        $this->assertEquals($res->titulo, 'Mi supermercado');
    }

    public function testAddGetProductoSupermercado() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '9876431',
            'codigo_rfid' => '123654789',
            'id_supermercado' => $idSupermercado
        );

        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProducto);
        $this->assertTrue($res !== NULL);
        $idProducto = $res['id_producto'];

        $res = $this->CI->supermercado_library->get_producto_supermercado($idProducto);
        $this->assertEquals($res->titulo, $datosProducto['titulo']);
        $this->assertEquals($res->descripcion, $datosProducto['descripcion']);
        $this->assertEquals($res->unidades, $datosProducto['unidades']);
        $this->assertEquals($res->precio, $datosProducto['precio']);
        $this->assertEquals($res->codigo_barras, $datosProducto['codigo_barras']);
        $this->assertEquals($res->codigo_rfid, $datosProducto['codigo_rfid']);
        $this->assertEquals($res->id_supermercado, $idSupermercado);
        $this->assertEquals($res->titulo_supermercado, 'Mi supermercado');
    }

    public function testSearchProductoCodigoBarras() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '9876431',
            'codigo_rfid' => '123654789',
            'id_supermercado' => $idSupermercado
        );

        $res = $this->CI->supermercado_library->search_productos_codigo_barras($datosProducto['codigo_barras']);
        $this->assertEquals(count($res), 0);

        $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProducto);

        $res = $this->CI->supermercado_library->search_productos_codigo_barras($datosProducto['codigo_barras']);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->titulo, $datosProducto['titulo']);
        $this->assertEquals($res[0]->descripcion, $datosProducto['descripcion']);
        $this->assertEquals($res[0]->unidades, $datosProducto['unidades']);
        $this->assertEquals($res[0]->precio, $datosProducto['precio']);
        $this->assertEquals($res[0]->codigo_barras, $datosProducto['codigo_barras']);
        $this->assertEquals($res[0]->codigo_rfid, $datosProducto['codigo_rfid']);
        $this->assertEquals($res[0]->id_supermercado, $idSupermercado);
        $this->assertEquals($res[0]->titulo_supermercado, 'Mi supermercado');
    }

    public function testSearchProductoCodigoRfid() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '9876431',
            'codigo_rfid' => '423654789',
            'id_supermercado' => $idSupermercado
        );

        $res = $this->CI->supermercado_library->search_productos_rfid($datosProducto['codigo_rfid']);
        $this->assertEquals(count($res), 0);

        $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProducto);

        $res = $this->CI->supermercado_library->search_productos_rfid($datosProducto['codigo_rfid']);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->titulo, $datosProducto['titulo']);
        $this->assertEquals($res[0]->descripcion, $datosProducto['descripcion']);
        $this->assertEquals($res[0]->unidades, $datosProducto['unidades']);
        $this->assertEquals($res[0]->precio, $datosProducto['precio']);
        $this->assertEquals($res[0]->codigo_barras, $datosProducto['codigo_barras']);
        $this->assertEquals($res[0]->codigo_rfid, $datosProducto['codigo_rfid']);
        $this->assertEquals($res[0]->id_supermercado, $idSupermercado);
        $this->assertEquals($res[0]->titulo_supermercado, 'Mi supermercado');
    }

    public function testErrorAddProductoSupermercado() {
        $res = $this->CI->supermercado_library->crear_supermercado('Mi supermercado');
        $idSupermercado = $res['supermercado_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => 9876431,
            'codigo_rfid' => '123654789',
            'id_supermercado' => $idSupermercado
        );
        $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProducto);

        $datosProductoSameCodigoBarras = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => 9876431,
            'codigo_rfid' => '64465654',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoSameCodigoBarras);;
        $this->assertNull($res);

        $datosProductoSameCodigoRfid = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => 31423,
            'codigo_rfid' => '123654789',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoSameCodigoRfid);;
        $this->assertNull($res);

        $datosProductoInvalidPrecio = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => '1.2a3',
            'codigo_barras' => 675337653,
            'codigo_rfid' => '34255',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoInvalidPrecio);;
        $this->assertNull($res);

        $datosProductoInvalidUnidades = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => '1sr',
            'precio' => 1.23,
            'codigo_barras' => 34567,
            'codigo_rfid' => '67464',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoInvalidUnidades);;
        $this->assertNull($res);

        $datosProductoInvalidCodigoBarras = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '753fas4753',
            'codigo_rfid' => '151345',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoInvalidCodigoBarras);;
        $this->assertNull($res);

        $datosProductoInvalidTitulo = array(
            'titulo' => '',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '7554232',
            'codigo_rfid' => '14673',
            'id_supermercado' => $idSupermercado
        );
        $res = $this->CI->supermercado_library->anadir_producto_supermercado($idSupermercado, $datosProductoInvalidTitulo);;
        $this->assertNull($res);
    }
}