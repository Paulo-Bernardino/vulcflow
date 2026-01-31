<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

$input = json_decode(file_get_contents('php://input'), true);
if ($input) { 
    $_POST = array_merge($_POST, $input); 
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    echo json_encode(['success' => false, 'message' => 'Nenhuma ação especificada.']);
    exit;
}

try {
    $db = new Database();

    switch ($action) {

        case 'getInitialData':
            $shiftData = correntDateShiftCrew($db); 
            echo json_encode([
                'user' => $_SESSION['user_nome'] ?? 'SISTEMA',
                'equipe' => $shiftData['equipe'] ?? '0',
                'turno' => $shiftData['turno'] ?? 1
            ]);
            break;

        case 'get_gtcode':
        case 'searchGTPress':
            $press = $_POST['press'] ?? $_GET['press'] ?? '';
            $gt = searchGTPressLocal($db, $press);
            
            if ($action === 'searchGTPress') {
                header('Content-Type: text/plain');
                echo trim($gt);
            } else {
                echo json_encode(['success' => true, 'gt' => $gt]);
            }
            break;

        case 'save_inventory':
        case 'insertInventoryPress':
            $shiftData = correntDateShiftCrew($db);
            $turno    = $_POST['turn']   ?? $shiftData['turno'] ?? '1';
            $equipe   = $_POST['team']   ?? $shiftData['equipe'] ?? '0';
            $linha    = strtoupper($_POST['linha'] ?? $_POST['lines'] ?? '');
            $prensa   = strtoupper(trim($_POST['cavidade'] ?? $_POST['press'] ?? ''));
            $gtcode   = strtoupper(trim($_POST['gtcode'] ?? $_POST['pneu'] ?? ''));
            $suporte  = (int)($_POST['qtd_suporte'] ?? 0);
            $prensa_q = (int)($_POST['qtd_prensa'] ?? 0);
            $user     = $_SESSION['user_nome'] ?? 'SISTEMA';

            $sql = "INSERT INTO TB_INVPRESS_B2 (DATA, TURNO, EQUIPE, LINES, PRESS, OPERADOR, PNEU, QTDPRESS, QTDFRONT) 
                    VALUES (SYSDATE, '{$turno}', '{$equipe}', '{$linha}', '{$prensa}', '{$user}', '{$gtcode}', {$suporte}, {$prensa_q})";

            $exec = $db->query($sql);
            echo json_encode(['success' => !!$exec, 'message' => $exec ? '✅ Inventário salvo!' : '❌ Erro no banco.']);
            break;

        case 'save_lubrificacao':
            $shiftData = correntDateShiftCrew($db);
            $date_val = $_POST['data_validade'] ?? ''; 
            $lines    = strtoupper($_POST['linha'] ?? '');
            $cavity   = strtoupper(trim($_POST['cavidade'] ?? ''));
            $barcode  = strtoupper(trim($_POST['barcode'] ?? ''));
            $shift    = (int)($shiftData['turno'] ?? 1);
            $team     = $shiftData['equipe'] ?? '0';
            $user     = $_SESSION['user_nome'] ?? 'SISTEMA';

            $sql = "INSERT INTO VULC_LUBRIFICATION@BR_DIVB2 (DATE_VALIDATE, LINES, CAVITY, BARCODE, SHIFT, TEAM, USERID) 
                    VALUES (TO_DATE('{$date_val}', 'YYYY-MM-DD'), '{$lines}', '{$cavity}', '{$barcode}', {$shift}, '{$team}', '{$user}')";

            $exec = $db->query($sql);
            echo json_encode(['success' => !!$exec, 'message' => $exec ? '✅ Lubrificação registrada!' : '❌ Erro no banco @BR_DIVB2.']);
            break;
            
        case 'send_new_order_alert':
            $address = $_POST['bipar'] ?? ''; 
            $msg     = $_POST['mensagem'] ?? '';

            if (empty($address)) {
                echo json_encode(['success' => false, 'message' => 'Destinatário vazio.']);
                exit;
            }

            // Mapeamento de Pagers por Linha
            if ($address === 'N' || $address === 'O') $address = '813,814';
            elseif ($address === 'P' || $address === 'Q') $address = '815,816';
            elseif ($address === 'R' || $address === 'S' || $address === 'T') $address = '817,818,822';

            $result = sendBipAlert($address, $msg);
            echo json_encode($result);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação desconhecida: ' . $action]);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}

// --- FUNÇÕES AUXILIARES ---

function searchGTPressLocal($db, $press) {
    $press = strtoupper(trim($press));
    if (empty($press)) return "";
    $sql = "SELECT CODGT FROM TB_MAPA_MOV MM
            LEFT JOIN TB_PNEU P ON P.IDPNEU = MM.IDPNEU
            JOIN TB_CAVIDADE C ON C.IDCAVIDADE = MM.IDCAVIDADE
            WHERE C.DESCRICAO = '{$press}'";
    $res = $db->query($sql);
    $result = $res ? $res->fetch(\PDO::FETCH_ASSOC) : null;
    return $result['CODGT'] ?? "";
}

function correntDateShiftCrew($db) {
    $hora = new \DateTime();
    $horaAtual = $hora->format('H:i:s');
    $turno = 1;
    foreach (Settings::BUILDING_SHIFT as $t => $intervalo) {
        $start = $intervalo['START']; $finish = $intervalo['FINISH'];
        if ($t === 3) { if ($horaAtual >= $start || $horaAtual <= $finish) { $turno = 3; break; } }
        else { if ($horaAtual >= $start && $horaAtual <= $finish) { $turno = $t; break; } }
    }
    $sql = "SELECT ESC_EQUIPE FROM FAM_ESCALA@BR_FAM WHERE ESC_TURNO = $turno AND ESC_DATA = TO_DATE('" . $hora->format('d/m/Y') . "','DD/MM/YYYY')";
    $query = $db->query($sql);
    $result = $query ? $query->fetch(\PDO::FETCH_OBJ) : null;
    return ['turno' => $turno, 'equipe' => $result->ESC_EQUIPE ?? '0'];
}

function sendBipAlert($address, $message) {
    if (empty($address)) return ['success' => false, 'message' => 'Nenhum destinatário.'];
    $url = 'http://netpage/multitone/pager?';
    $fields = ['src' => 'API::BIP', 'address' => $address, 'msg' => strtoupper($message)];
    $parameters = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . $parameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $output = curl_exec($ch);
    curl_close($ch);
    return (trim($output) == 'OK') ? ['success' => true, 'message' => 'BIP enviado!'] : ['success' => false, 'message' => $output];
}