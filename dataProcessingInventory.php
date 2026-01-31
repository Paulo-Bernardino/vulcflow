<?php

class DataProcessingInventory
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function correntDateShiftCrew()
    {
        $hora = new \DateTime();
        $horaAtual = $hora->format('H:i:s');

        $data_inicial = null;
        $data_final   = null;
        $turno        = null;

        foreach (Settings::BUILDING_SHIFT as $t => $intervalo) {
            $start = $intervalo['START'];
            $finish = $intervalo['FINISH'];

            if ($t === 3) {
                if ($horaAtual >= $start || $horaAtual <= $finish) {
                    if ($horaAtual >= $start) {
                        $data_inicial = $hora->format('d/m/Y') . ' ' . $start;
                        $hora->add(new \DateInterval('P1D'));
                        $data_final = $hora->format('d/m/Y') . ' ' . $finish;
                    } else {
                        $data_final = $hora->format('d/m/Y') . ' ' . $finish;
                        $hora->sub(new \DateInterval('P1D'));
                        $data_inicial = $hora->format('d/m/Y') . ' ' . $start;
                    }
                    $turno = 3;
                    break;
                }
            } else {
                if ($horaAtual >= $start && $horaAtual <= $finish) {
                    $data_inicial = $hora->format('d/m/Y') . ' ' . $start;
                    $data_final   = $hora->format('d/m/Y') . ' ' . $finish;
                    $turno = $t;
                    break;
                }
            }
        }

        $sql = "SELECT * FROM FAM_ESCALA@BR_FAM
                WHERE ESC_TURNO = $turno
                AND ESC_DATA = TO_DATE('" . $hora->format('d/m/Y') . "','DD/MM/YYYY')";

        $query = $this->db->query($sql);
        $result = $query->fetch(\PDO::FETCH_OBJ);

        return [
            'turno'       => $turno,
            'datainicial' => $data_inicial,
            'datafinal'   => $data_final,
            'equipe'      => $result->ESC_EQUIPE ?? null
        ];
    }

   public function insertInventoryPress(array $data)
{
    $required = ['turn', 'team', 'user', 'lines', 'press', 'gtcode'];
    foreach ($required as $field) {
        if (empty($data[$field]) || $data[$field] === 'Selecione') {
            return ['msg' => "❌ Campo {$field} inválido!", 'type' => 'danger'];
        }
    }

    $turno    = strtoupper($data['turn']);
    $equipe   = strtoupper($data['team']);
    $linha    = strtoupper($data['lines']);
    $prensa   = strtoupper($data['press']);
    $operador = addslashes(strtoupper($data['user']));
    $pneu     = addslashes(strtoupper($data['gtcode']));

    $qtdPress = isset($data['qtdsupport']) ? (int)$data['qtdsupport'] : 0;
    $qtdFront = isset($data['qtdfrontpress']) ? (int)$data['qtdfrontpress'] : 0;

    try {
        $sql = "INSERT INTO TB_INVPRESS_B2 (
                    DATA,
                    TURNO,
                    EQUIPE,
                    LINES,
                    PRESS,
                    OPERADOR,
                    PNEU,
                    QTDPRESS,
                    QTDFRONT
                ) VALUES (
                    SYSDATE,
                    '{$turno}',
                    '{$equipe}',
                    '{$linha}',
                    '{$prensa}',
                    '{$operador}',
                    '{$pneu}',
                    {$qtdPress},
                    {$qtdFront}
                )";

        $this->db->query($sql);

        return ['msg' => '✅ Registro inserido com sucesso!', 'type' => 'success'];
    } catch (\PDOException $e) {
        return ['msg' => '❌ Erro ao inserir: ' . $e->getMessage(), 'type' => 'danger'];
    }
}



    public function insertInventory(array $data)
    {
        $required = ['turn', 'team', 'user', 'pneu', 'quantidade', 'retido'];
        foreach ($required as $field) {
            if (empty($data[$field]) || $data[$field] === 'Selecione') {
                return ['msg' => "❌ Campo {$field} inválido!", 'type' => 'danger'];
            }
        }

        if (strtolower($data['retido']) !== 'sim' && (empty($data['barcode']) || $data['barcode'] === 'Selecione')) {
            return ['msg' => "❌ Campo barcode inválido!", 'type' => 'danger'];
        }

        $turno   = strtoupper($data['turn']);
        $equipe  = strtoupper($data['team']);
        $barcode = strtoupper($data['barcode']);
        $operador = strtoupper($data['user']);
        $pneu    = strtoupper($data['pneu']);
        $quantidade = (int)$data['quantidade'];
        $retido  = strtolower($data['retido']);
        $errado  = !empty($data['errado']) && strtolower($data['errado']) === 'sim' ? 'SIM' : 'NAO';

        try {
            $sql = "INSERT INTO TB_INV_B2 (
                        DATA,
                        TURNO,
                        EQUIPE,
                        BARCODE,
                        OPERADOR,
                        PNEU,
                        QUANTIDADE,
                        RETIDO,
                        ERRADO
                    ) VALUES (
                        SYSDATE,
                        '{$turno}',
                        '{$equipe}',
                        '{$barcode}',
                        '{$operador}',
                        '{$pneu}',
                        {$quantidade},
                        '{$retido}',
                        '{$errado}'
                    )";

            $this->db->query($sql);

            return ['msg' => '', 'type' => 'success'];
        } catch (\PDOException $e) {
            return ['msg' => '❌ Erro ao inserir: ' . $e->getMessage(), 'type' => 'danger'];
        }
    }

    public function getPrensaStatus(int $turno, string $dataFiltro): array
    {
    $dataFormat = date('d/m/Y', strtotime($dataFiltro));

    $sql = "SELECT
            LINES,
            PRESS,
            SUM(QTDPRESS) AS QTDPRESS,
            SUM(QTDFRONT) AS QTDFRONT
        FROM
            TB_INVPRESS_B2
        WHERE
            TURNO = :turno
            AND TRUNC(DATA) = TO_DATE(:data, 'DD/MM/YYYY')
        GROUP BY
            LINES, PRESS";            

    $params = [
    ':turno' => $turno,
    ':data' => $dataFormat
    ];

    $results = $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

    $prensasComDados = [];
    foreach ($results as $row) {
    $linha = $row['LINES'];
    $prensa = substr($row['PRESS'], 1);
    if (!isset($prensasComDados[$linha])) {
        $prensasComDados[$linha] = [];
    }
    $prensasComDados[$linha][$prensa] = [
        'qtdpress' => (int) $row['QTDPRESS'],
        'qtdfront' => (int) $row['QTDFRONT']
    ];
    }

    return $prensasComDados;
    }

public function getReport(array $filters): array
{
    $dataFiltro = $filters['data'] ?? date('Y-m-d');
    $dataFormat = date('d/m/Y', strtotime($dataFiltro));
    $turno      = (int) ($filters['turno'] ?? 1);

    $sql = "SELECT 
                TO_CHAR(DATA,'DD/MM/YYYY') AS DATA,
                EQUIPE,
                TURNO,
                OPERADOR,
                BARCODE,
                PNEU,
                QUANTIDADE,
                RETIDO,
                ERRADO
            FROM TB_INV_B2
            WHERE TRUNC(DATA) = TO_DATE('{$dataFormat}', 'DD/MM/YYYY')
              AND TURNO = {$turno}
            ORDER BY PNEU";

    $registros = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    $totais = ['normal' => [], 'retido' => []];

    foreach ($registros as $i => $row) {
        $pneu  = trim($row['PNEU']);
        $qtd   = (int) $row['QUANTIDADE'];
        $valor = strtoupper(trim((string) $row['RETIDO']));
        $grupo = in_array($valor, Settings::VALORES_NAO_RETIDO, true) ? 'normal' : 'retido';
        $totais[$grupo][$pneu] = ($totais[$grupo][$pneu] ?? 0) + $qtd;

        $registros[$i] = [
            'DATA'       => $row['DATA'] ?? '',
            'EQUIPE'     => $row['EQUIPE'] ?? '',
            'TURNO'      => $row['TURNO'] ?? '',
            'OPERADOR'   => $row['OPERADOR'] ?? '',
            'BARCODE'    => $row['BARCODE'] ?? '',
            'PNEU'       => $pneu,
            'QUANTIDADE' => $qtd,
            'RETIDO'     => $valor,
            'ERRADO'     => $row['ERRADO'] ?? 'Não',
            'ORIGEM'     => 'B2'
        ];
    }

    $sqlPress = "SELECT
                    TO_CHAR(DATA,'DD/MM/YYYY') AS DATA,
                    EQUIPE,
                    TURNO,
                    OPERADOR,
                    TRIM(PNEU) AS PNEU,
                    SUM(NVL(QTDPRESS,0) + NVL(QTDFRONT,0)) AS QTD
                 FROM TB_INVPRESS_B2
                 WHERE TRUNC(DATA) = TO_DATE('{$dataFormat}', 'DD/MM/YYYY')
                   AND TURNO = {$turno}
                 GROUP BY TO_CHAR(DATA,'DD/MM/YYYY'), EQUIPE, TURNO, OPERADOR, TRIM(PNEU)
                 ORDER BY PNEU";

    $pressData = $this->db->query($sqlPress)->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($pressData as $row) {
        $pneu  = trim($row['PNEU']);
        $qtd   = (int) $row['QTD'];

        // Atualizar totais
        $totais['normal'][$pneu] = ($totais['normal'][$pneu] ?? 0) + $qtd;

        // Preencher campos para frontend
        $registros[] = [
            'DATA'       => $row['DATA'] ?? '',
            'EQUIPE'     => $row['EQUIPE'] ?? '',
            'TURNO'      => $row['TURNO'] ?? '',
            'OPERADOR'   => $row['OPERADOR'] ?? '',
            'BARCODE'    => '',
            'PNEU'       => $pneu,
            'QUANTIDADE' => $qtd,
            'RETIDO'     => 'Não',
            'ERRADO'     => 'Não',
            'ORIGEM'     => 'PRESS'
        ];
    }

    return [
        'filtros'   => ['data' => $dataFiltro, 'turno' => $turno],
        'registros' => $registros,
        'totais'    => $totais
    ];
}



    public function searchGT(string $barcode): string
    {
        $barcode = strtoupper(trim($barcode));

        if (strlen($barcode) < 6) {
            return "";
        }

        $sql = "SELECT 
                    TIC 
                FROM
                    l1bc.barcode@BRAMRT01_GSREADS 
                WHERE
                    BARCODE = '" . $barcode . "'";

        $result = $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);

        return $result['TIC'] ?? "";
    }

    public function searchGTPress(string $press): string
    {
        $press = strtoupper(trim($press));

        if ($press === '') {
            return "";
        }

        $sql = "SELECT
                CODGT
            FROM
                TB_MAPA_MOV MM
                LEFT JOIN TB_PNEU P ON P.IDPNEU = MM.IDPNEU
                JOIN TB_CAVIDADE C ON C.IDCAVIDADE = MM.IDCAVIDADE
            WHERE
                C.DESCRICAO = '{$press}'";

        $result = $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);

        return $result['CODGT'] ?? "";
    }

    public function remakeInventory(string $data, int $turno): array
    {
        $dataFormat = date('d/m/Y', strtotime($data));

        try {
            $sqlInv = "DELETE FROM TB_INV_B2 
                       WHERE TRUNC(DATA) = TO_DATE('{$dataFormat}', 'DD/MM/YYYY') 
                         AND TURNO = {$turno}";
            $this->db->query($sqlInv);

            $sqlPress = "DELETE FROM TB_INVPRESS_B2 
                         WHERE TRUNC(DATA) = TO_DATE('{$dataFormat}', 'DD/MM/YYYY') 
                           AND TURNO = {$turno}";
            $this->db->query($sqlPress);

            return [
                'msg'  => "✅ Inventário e Prensas do turno {$turno} em {$dataFormat} foram apagados com sucesso!",
                'type' => 'success'
            ];
        } catch (\PDOException $e) {
            return [
                'msg'  => "❌ Erro ao apagar inventário: " . $e->getMessage(),
                'type' => 'danger'
            ];
        }
    }
}
