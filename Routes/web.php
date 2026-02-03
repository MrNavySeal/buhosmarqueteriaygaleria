<?php
    //Modulos
    $router->setRoute("modulos/","Modulos/Modulos",false);
    $router->setRoute("modulos/secciones/","Modulos/Secciones",false);
    $router->setRoute("modulos/opciones/","Modulos/Opciones",false);

    //Dashboard
    $router->setRoute("dashboard/","Dashboard/Dashboard",false);

    //Sistema
    $router->setRoute("sistema/roles/","Sistema/Roles",false);
    $router->setRoute("sistema/usuarios/","Sistema/Usuarios",false);

    //Productos
    $router->setRoute("productos/categorias/","Productos/ProductosCategorias/categorias",false);
    $router->setRoute("productos/subcategorias/","Productos/ProductosCategorias/subcategorias",false);
    $router->setRoute("productos/creacion-edicion-masiva/","Productos/ProductosMasivos/productos",false);
    $router->setRoute("productos/","Productos/Productos",false);
    $router->setRoute("productos/variantes/","Productos/ProductosOpciones/variantes",false);
    $router->setRoute("productos/unidades-medida/","Productos/ProductosOpciones/unidades",false);
    $router->setRoute("productos/caracteristicas/","Productos/ProductosOpciones/caracteristicas",false);

    //Marquetería
    $router->setRoute("marqueteria/colores/","Marqueteria/Marqueteria/colores",false);
    $router->setRoute("marqueteria/categorias/","Marqueteria/Marqueteria/categorias",false);
    $router->setRoute("marqueteria/propiedades/","Marqueteria/Marqueteria/propiedades",false);
    $router->setRoute("marqueteria/opciones-propiedades/","Marqueteria/MarqueteriaOpciones/opciones",false);
    $router->setRoute("marqueteria/configuracion/","Marqueteria/MarqueteriaConfiguracion/configuracion",false);
    $router->setRoute("marqueteria/ejemplos/","Marqueteria/MarqueteriaEjemplos/ejemplos",false);
    $router->setRoute("marqueteria/fondos/","Marqueteria/MarqueteriaFondos/fondos",false);

    //Almacén
    $router->setRoute("almacen/inventario/","Almacen/Inventario/inventario",false);
    $router->setRoute("almacen/kardex/","Almacen/Inventario/kardex",false);
    $router->setRoute("almacen/ajuste-inventario/","Almacen/InventarioAjuste/ajuste",false);
    $router->setRoute("almacen/reporte-ajustes/","Almacen/InventarioAjuste/reporte",false);
    $router->setRoute("almacen/reporte-ajustes-detalle/","Almacen/InventarioAjuste/reporteDetalle",false);

    //Pedidos
    $router->setRoute("pedidos/cotizaciones/","Pedidos/Cotizaciones/cotizaciones",false);
    $router->setRoute("pedidos/","Pedidos/Pedidos",false);
    $router->setRoute("pedidos/pedidos-credito/","Pedidos/Pedidos/creditos",false);
    $router->setRoute("pedidos/pedidos-detalle/","Pedidos/Pedidos/detalle",false);
    $router->setRoute("pedidos/punto-venta/","Pedidos/PedidosPos/venta",false);
    $router->setRoute("pedidos/transaccion/","Pedidos/Pedidos/transaccion");
    $router->setRoute("pedidos/factura/","Pedidos/Pedidos/pdf");
    $router->setRoute("pedidos/reporte/","Pedidos/Pedidos/reportePedidos",false);

    //Compras
    $router->setRoute("compras/","Compras/Compras/compras",false);
    $router->setRoute("compras/nueva-compra/","Compras/Compras/compra",false);
    $router->setRoute("compras/compras-credito/","Compras/Compras/creditos",false);
    $router->setRoute("compras/compras-detalle/","Compras/Compras/detalles",false);
    $router->setRoute("compras/proveedores/","Compras/Proveedores/proveedores",false);

    //Contabilidad
    $router->setRoute("contabilidad/categorias/","Contabilidad/Contabilidad/categorias",false);
    $router->setRoute("contabilidad/movimientos/","Contabilidad/Contabilidad/movimientos",false);
    $router->setRoute("contabilidad/informe/","Contabilidad/Contabilidad/informe",false);
    $router->setRoute("contabilidad/cuentas-contables/","Contabilidad/CuentasContables/cuentas",false);
    $router->setRoute("contabilidad/conceptos-contables/","Contabilidad/ConceptosContables/conceptos",false);
    $router->setRoute("contabilidad/tipo-conceptos/","Contabilidad/TipoConceptos/tipos",false);

    //Tesoreria
    $router->setRoute("tesoreria/retenciones/","Tesoreria/Retenciones/retenciones",false);

    //Configuracion
    $router->setRoute("configuracion/parametros/","Configuracion/Empresa/empresa",false);
    $router->setRoute("configuracion/envios/","Configuracion/Administracion/envios",false);
    $router->setRoute("configuracion/paginas/","Configuracion/Paginas/paginas",false);
    $router->setRoute("configuracion/pagina/","Configuracion/Paginas/pagina",true);
    $router->setRoute("configuracion/banners/","Configuracion/Banners/banners",false);
    $router->setRoute("configuracion/medios-de-pago/","Configuracion/MedioPagos/medioPagos",false);
    $router->setRoute("configuracion/terceros/","Configuracion/Terceros/terceros",false);

    //Marketing
    $router->setRoute("marketing/cupones/","Marketing/Descuentos/cupones",false);
    $router->setRoute("marketing/descuentos/","Marketing/Descuentos/descuentos",false);
    $router->setRoute("marketing/suscriptores/","Configuracion/Administracion/suscriptores",false);
    $router->setRoute("marketing/correo/","Configuracion/Administracion/correo",false);
    $router->setRoute("marketing/faq/","Marketing/Faq/faq",false);
    $router->setRoute("marketing/faq-secciones/","Marketing/Faq/secciones",false);

    //Comentarios
    $router->setRoute("comentarios/","Comentarios/Comentarios/comentarios",false);
    $router->setRoute("comentarios/opiniones/","Comentarios/Comentarios/opiniones",false);

    //Blog
    $router->setRoute("noticias/","Noticias/Articulos/articulos",false);
    $router->setRoute("noticias/articulo/","Noticias/Articulos/articulo");

    //Tienda
    $router->setRoute("paginas/faqs/","Paginas/Paginas/faqs");
?>