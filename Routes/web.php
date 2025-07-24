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

    //Clientes
    $router->setRoute("clientes/","Clientes/Clientes",false);

    //Productos
    $router->setRoute("productos/categorias/","Productos/ProductosCategorias/categorias",false);
    $router->setRoute("productos/subcategorias/","Productos/ProductosCategorias/subcategorias",false);
    $router->setRoute("productos/creacion-edicion-masiva/","Productos/ProductosMasivos/productos",false);
    $router->setRoute("productos/","Productos/Productos",false);
    $router->setRoute("productos/variantes/","Productos/ProductosOpciones/variantes",false);
    $router->setRoute("productos/unidades-medida/","Productos/ProductosOpciones/unidades",false);
    $router->setRoute("productos/caracteristicas/","Productos/ProductosOpciones/caracteristicas",false);

    //Marquetería
    $router->setRoute("marqueteria/colores/","Marqueteria/colores",false);
    $router->setRoute("marqueteria/categorias/","Marqueteria/categorias",false);
    $router->setRoute("marqueteria/opciones-propiedades/","Marqueteria/propiedades",false);
    $router->setRoute("marqueteria/categorias/","MarqueteriaOpciones/opciones",false);
    $router->setRoute("marqueteria/configuracion/","MarqueteriaConfiguracion/configuracion",false);
    $router->setRoute("marqueteria/ejemplos/","MarqueteriaEjemplos/ejemplos",false);

    //Almacén
    $router->setRoute("almacen/inventario/","Inventario/inventario",false);
    $router->setRoute("almacen/kardex/","Inventario/kardex",false);
    $router->setRoute("almacen/ajuste-inventario/","InventarioAjuste/ajuste",false);
    $router->setRoute("almacen/reporte-ajustes/","InventarioAjuste/reporte",false);
    $router->setRoute("almacen/reporte-ajustes-detalle/","InventarioAjuste/reporteDetalle",false);

    //Pedidos
    $router->setRoute("pedidos/cotizaciones/","Cotizaciones/cotizaciones",false);
    $router->setRoute("pedidos/","Pedidos/pedidos",false);
    $router->setRoute("pedidos/pedidos-credito/","Pedidos/creditos",false);
    $router->setRoute("pedidos/pedidos-detalle/","Pedidos/detalle",false);
    $router->setRoute("pedidos/punto-venta/","PedidosPos/venta",false);

    //Compras
    $router->setRoute("compras/","Compras/compras",false);
    $router->setRoute("compras/nueva-compra/","Compras/compra",false);
    $router->setRoute("compras/compras-credito/","Compras/creditos",false);
    $router->setRoute("compras/compras-detalle/","Compras/detalles",false);
    $router->setRoute("compras/proveedores/","Proveedores/proveedores",false);

    //Contabilidad
    $router->setRoute("contabilidad/categorias/","Contabilidad/categorias",false);
    $router->setRoute("contabilidad/movimientos/","Contabilidad/movimientos",false);
    $router->setRoute("contabilidad/informe/","Contabilidad/informe",false);

    //Configuracion
    $router->setRoute("configuracion/empresa/","Empresa/empresa",false);
    $router->setRoute("configuracion/envios/","Administracion/envios",false);
    $router->setRoute("configuracion/paginas/","Paginas/paginas",false);
    $router->setRoute("configuracion/banners/","Banners/banners",false);

    //Marketing
    $router->setRoute("marketing/cupones/","Descuentos/cupones",false);
    $router->setRoute("marketing/descuentos/","Descuentos/descuentos",false);
    $router->setRoute("marketing/suscriptores/","Administracion/suscriptores",false);
    $router->setRoute("marketing/correo/","Administracion/correo",false);

    //Comentarios
    $router->setRoute("comentarios/","Comentarios/comentarios",false);
    $router->setRoute("comentarios/opiniones/","Comentarios/opiniones",false);

    //Blog
    $router->setRoute("blog/","Articulos/articulos",false);
?>