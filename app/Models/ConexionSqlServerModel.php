<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ConexionSqlServerModel extends Model{    
    protected $sqlsrvDB;

    public function __construct()
    {
        $this->sqlsrvDB = Database::connect('sqlsrv');

        set_time_limit(0);
        ini_set("memory_limit", '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize','1012M'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','1012M'); // Setting to 512M - for pdo_sqlsrv
    }

    public function obtenerProductosActivos(){
        try {
            $sql = "SELECT DISTINCT CODIGO FROM Matriz_Puntofarma";
            log_message('debug', 'ENTRO');
            $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
            log_message('debug', 'TERMINO');

            return $respuesta;
        } catch (\Throwable $e) {
            log_message('error', $e);
            return [];
        }
    }

    public function obtenerProductosActivos2(){
        try {
            $obtenerProductosActivos2 = verificarRedis('obtenerProductosActivos2');
            if($obtenerProductosActivos2 == null){
                $sql = "SELECT 
                    CAST(Codigo_Producto AS INT) AS id_producto
                    FROM Ventas v 
                    WHERE Clase_Factura not in ('ZOTR', 'ZDOM', 'ZMUE', 'S1', 'S2')
                        AND TRY_CAST(CONCAT(Anho, '-', Mes, '-', Dia) AS DATE) >= DATEADD(YEAR, -1, GETDATE())
                    group by Codigo_Producto
                    order by Codigo_Producto desc
                ";
    
                // log_message('debug', 'ENTRO');
                $obtenerProductosActivos2 = $this->sqlsrvDB->query($sql)->getResultArray();
                // log_message('debug', 'TERMINO');

                almacenarRedis('obtenerProductosActivos2', [], $obtenerProductosActivos2, 60);
            }

            return $obtenerProductosActivos2;
        } catch (\Throwable $e) {
            log_message('error', $e);
            return [];
        }
    }

    public function obtenerIgmVentas(){
        try {
            $obtenerIgmVentas = verificarRedis('obtenerIgmVentas');
            $obtenerIgmVentas = null;

            if($obtenerIgmVentas == null){
                $sql = "SELECT 
                    CAST(Codigo_Cliente AS INT) AS id_cliente
                    , Cliente as cliente
                    , CAST(Codigo_Vendedor AS INT) AS id_vendedor
                    , Vendedor as vendedor
                    , CAST(Codigo_Producto AS INT) AS id_producto
                    , PRODUCTO as producto
                    , Grupo_Vendedor as grupo_vendedor
                    , ObjetivoAño AS objetivo_anho
                    FROM IGM_Ventas_Congelado 
                    WHERE Codigo_Cliente IN ('0000006176')
                    group by Codigo_Cliente, Cliente, Codigo_Vendedor, Vendedor, Codigo_Producto, PRODUCTO, Grupo_Vendedor, ObjetivoAño
                    order by Codigo_Producto ASC
                ";
    
                // log_message('debug', $sql);
                $obtenerIgmVentas = $this->sqlsrvDB->query($sql)->getResultArray();
                // log_message('debug', 'TERMINO');

                almacenarRedis('obtenerIgmVentas', [], $obtenerIgmVentas, 60);
            }

            return $obtenerIgmVentas;
        } catch (\Throwable $e) {
            log_message('error', $e);
            return [];
        }
    }
    
    public function obtenerHistoricoVentasPuntoFarma($pagina = 0){
        try {
            $inicio = $pagina * 500000;
            log_message('debug', "INICIO: $inicio");

            $sql = "
                select 
                    6176 as id_cliente,
                    [\"PROD_CODIGO\"] AS id_producto_cadena,
                    [\"PRODUCTO\"] AS producto,
                    [\"ESTR_CODIGO\"] AS id_sucursal,
                    [\"ESTRUCTURA\"] AS sucursal,
                    FORMAT(CONVERT(date, [\"PERIODO\"], 103), 'yyyy-MM-dd') AS fecha, -- Formatear la fecha
                    REPLACE([\"COD_BARRA\"], '\"', '') AS codigo_barra, -- Eliminar comillas dobles en los valores
                    [\"CANTIDAD\"] AS cantidad,
                    [\"VALORIZADO\"] AS valor
                from Ventas_Puntofarma
                ORDER BY fecha ASC
                OFFSET $inicio ROWS FETCH NEXT 500000 ROWS ONLY
            ";

            log_message('debug', 'ENTRO');
            $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
            log_message('debug', 'TERMINO');

            return $respuesta;
        } catch (\Throwable $e) {
            log_message('error', $e);
            return [];
        }
    }

    public function obtenerVentasCadenas(){
        try {
            $sql = "SELECT *, 6176 AS id_cliente FROM VISTA_VENTAS_PUNTOFARMA";
            log_message('debug', 'ENTRO');
            $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
            log_message('debug', 'TERMINO');

            return $respuesta;
        } catch (\Throwable $e) {
            log_message('error', $e);
            return [];
        }
    }

    public function obtenerStockCadenas()
    {
        $sql = "SELECT *, 6176 AS id_cliente FROM Stock_Puntofarma";
        log_message('debug', 'ENTRO');
        $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
        log_message('debug', 'TERMINO');

        return $respuesta;
    }

    public function obtenerStockDistribuidora($filtros = [])
    {
        log_message('debug', json_encode($filtros, JSON_PRETTY_PRINT));
        $columnas = array_column($filtros['columns'], 'name');
        $orden = (array) $filtros['order'];
        $inicio = (int) $filtros['start'];
        $cantidad = (int) $filtros['length'];
        $draw = (int) $filtros['draw'];

        $orderBy = "";
        foreach ($orden as $key => $val) {
            $columna = $val['column'];
            $dir = $val['dir'];

            if($key > 0 && !empty($orderBy)) $orderBy .= ', '; 
            if($columnas[$columna] == 'reposicion'){
                $orderBy .= "$columnas[$columna] $dir";
                continue;
            }

            $orderBy .= "$columnas[$columna] $dir";
        }

        // log_message('debug', ($orderBy));

        $condiciones = '';
        if(!empty($filtros['id_producto'])){
            $ids = implode(',', (array) $filtros['id_producto']);
            $condiciones = "AND codigo IN ($ids)";
        }

        $sql = "WITH CTE_STOCK AS (
                SELECT 
                    CODIGO,
                    SUM(CASE WHEN COD_SUCURSAL = 2 THEN STOCK ELSE 0 END) AS stock_sucursal_2,
                    SUM(CASE WHEN COD_SUCURSAL != 2 THEN STOCK ELSE 0 END) AS stock_otras_sucursales
                FROM VISTA_VENTAS_PUNTOFARMA
                GROUP BY CODIGO
            )
            SELECT 
                VVP.CODIGO AS codigo,
                VVP.PRODUCTO AS producto,
                SUM(VVP.CANTIDAD) AS cantidad,
                COALESCE(CS.stock_sucursal_2, 0) AS stock,
                COALESCE(CS.stock_otras_sucursales, 0) AS stock_sucursales,
                SUM(VVP.VALOR) AS valor,
                (COALESCE(CS.stock_sucursal_2, 0) / (SUM(VVP.CANTIDAD) / 12)) AS rv,
                (SUM(VVP.CANTIDAD) / 12) AS promedio,
                ((SUM(VVP.CANTIDAD) / 12) * 4) AS stock_max,
                (((SUM(VVP.CANTIDAD) / 12) * 4) - COALESCE(CS.stock_sucursal_2, 0)) AS reposicion
            FROM VISTA_VENTAS_PUNTOFARMA AS VVP
            LEFT JOIN CTE_STOCK AS CS
                ON VVP.CODIGO = CS.CODIGO
            WHERE 1 = 1
            $condiciones
            GROUP BY VVP.CODIGO, VVP.PRODUCTO, CS.stock_sucursal_2, CS.stock_otras_sucursales
            ORDER BY $orderBy
            OFFSET $inicio ROWS FETCH NEXT $cantidad ROWS ONLY;
        ";

        $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();

        $totalRegistros = $this->sqlsrvDB->query("SELECT COUNT(*) AS total FROM VISTA_VENTAS_PUNTOFARMA WHERE COD_SUCURSAL = 2")->getRowArray();
        $totalRegistrosFiltrados = $this->sqlsrvDB->query("SELECT COUNT(*) AS total FROM VISTA_VENTAS_PUNTOFARMA WHERE COD_SUCURSAL = 2 $condiciones")->getRowArray();
        
        // log_message('debug', json_encode($count));
        // log_message('debug', count($respuesta));
        // log_message('debug', count($respuesta2));

        $data['data'] = $respuesta;
        $data['draw'] = $draw;
        $data['recordsTotal'] = !empty($totalRegistros) ? $totalRegistros['total'] : 0;
        $data['recordsFiltered'] = !empty($totalRegistrosFiltrados) ? $totalRegistrosFiltrados['total'] : 0;
        // log_message('debug', json_encode($data));
        return $data;
        
        return $respuesta;
    }

    public function obtenerProductosReponerSucursales()
    {     
        $sql = "
        SELECT 
            COD_SUCURSAL as codigo_sucursal
            , SUCURSAL as sucursal
            , CODIGO as codigo
            , PRODUCTO as producto
            , CANTIDAD AS cantidad
            , STOCK AS stock
            , VALOR AS valor
            , (CANTIDAD / 12) AS promedio
            , ((CANTIDAD / 12) * 4) AS stock_max
            , (((CANTIDAD / 12) * 4) - STOCK) AS reposicion
        FROM VISTA_VENTAS_PUNTOFARMA
        $where
        ";

        $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
        
        return $respuesta;
    }
    
    public function obtenerVentasPuntoFarma()
    {        
        $sql = "SELECT * FROM VISTA_VENTAS_PUNTOFARMA";

        $respuesta = $this->sqlsrvDB->query($sql)->getResultArray();
        
        return $respuesta;
    }

    public function obtenerInformacionTotales()
    {        
        $sql = "
        SELECT 
            COUNT(DISTINCT COD_SUCURSAL) AS total
        FROM 
            VISTA_VENTAS_PUNTOFARMA
        ";
        
        $totalSucursales = $this->sqlsrvDB->query($sql)->getRowArray();
        
        $sql = "
        SELECT 
            COUNT(DISTINCT COD_SUCURSAL) AS total
        FROM 
            VISTA_VENTAS_PUNTOFARMA
        WHERE 
            (((CANTIDAD / 12) * 4) - STOCK) > 0
        ";

        $totalSucFaltantes = $this->sqlsrvDB->query($sql)->getRowArray();

        $respuesta = [
            'totalSucursales'   => $totalSucursales,
            'totalSucFaltantes' => $totalSucFaltantes,
        ];
        
        log_message('debug', json_encode($respuesta));
        return $respuesta;
    }

    public function obtenerSucursalesProductosFaltantes($filtros = [])
    {    
        // log_message('debug', 'ENTRO');
        // log_message('debug', json_encode($filtros, JSON_PRETTY_PRINT));
        $columnas = array_column($filtros['columns'], 'name');
        $orden = (array) $filtros['order'];
        $inicio = (int) $filtros['start'];
        $cantidad = (int) $filtros['length'];
        $draw = (int) $filtros['draw'];

        $orderBy = "";
        foreach ($orden as $key => $val) {
            $columna = $val['column'];
            $dir = $val['dir'];

            if($key > 0 && !empty($orderBy)) $orderBy .= ', '; 
            if($columnas[$columna] == 'reposicion'){
                $orderBy .= "$columnas[$columna] $dir";
                continue;
            }

            $orderBy .= "$columnas[$columna] $dir";
        }

        $condiciones = '';
        if(!empty($filtros['id_sucursal'])) $condiciones .= " AND COD_SUCURSAL = $filtros[id_sucursal]";
        if(!empty($filtros['id_producto'])){
            $ids = implode(',', $filtros['id_producto']);
            $condiciones .= " AND CODIGO IN ($ids)";
        }

        $where = 'WHERE COD_SUCURSAL != 2';
        if(!empty($filtros['productosFaltantes'])) $where .= ' AND (((CANTIDAD / 12) * 4) - STOCK) > 0';

        // log_message('debug', ($orderBy));
        
        $sql = "SELECT 
            COD_SUCURSAL as codigo_sucursal
            , SUCURSAL as sucursal
            , CODIGO as codigo
            , PRODUCTO as producto
            , CANTIDAD AS cantidad
            , STOCK AS stock
            , VALOR AS valor
            , 0 AS rv
            , (CANTIDAD / 12) AS promedio
            , ((CANTIDAD / 12) * 4) AS stock_max
            , (((CANTIDAD / 12) * 4) - STOCK) AS reposicion
        FROM VISTA_VENTAS_PUNTOFARMA
        $where
        $condiciones
        ORDER BY $orderBy OFFSET $inicio ROWS FETCH NEXT $cantidad ROWS ONLY
        ";
        
        // log_message('debug', ($sql));
        $query = $this->sqlsrvDB->query($sql);

        if ($query === false){
            log_message('error', sqlsrv_errors());
            return [];
        }
        // die(print_r(sqlsrv_errors(), true));
        
        $respuesta = $query->getResultArray();

        $totalRegistros = $this->sqlsrvDB->query("SELECT COUNT(*) AS total FROM VISTA_VENTAS_PUNTOFARMA $where")->getRowArray();
        $totalRegistrosFiltrados = $this->sqlsrvDB->query("SELECT COUNT(*) AS total FROM VISTA_VENTAS_PUNTOFARMA $where $condiciones")->getRowArray();
        
        // log_message('debug', json_encode($count));
        // log_message('debug', count($respuesta));
        // log_message('debug', count($respuesta2));

        $data['data'] = $respuesta;
        $data['draw'] = $draw;
        $data['recordsTotal'] = !empty($totalRegistros) ? $totalRegistros['total'] : 0;
        $data['recordsFiltered'] = !empty($totalRegistrosFiltrados) ? $totalRegistrosFiltrados['total'] : 0;
        // log_message('debug', json_encode($data));
        return $data;
    }
}