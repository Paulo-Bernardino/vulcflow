<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bipar'])) {
    $targetIds = explode(',', $_POST['bipar']);
    $messageText = strtoupper(trim($_POST['mensagem']));
    $successCount = 0;

    foreach ($targetIds as $id) {
        $apiUrl = 'http://netpage/multitone/pager?';
        
        $payload = [
            'src'     => 'API::BIP',
            'address' => trim($id),
            'msg'     => $messageText
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl . http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        
        $response = curl_exec($ch);
        if (trim($response) === 'OK') {
            $successCount++;
        }
        curl_close($ch);
    }

    $_SESSION['msg'] = ($successCount > 0) 
        ? "1|Alerta enviado para $successCount setor(es)!" 
        : "0|Erro ao processar o disparo.";
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include_once "header.html"; ?>

<main class="flex-1 p-4 md:p-8 max-w-lg mx-auto w-full animate-fadeIn">
    
    <?php if (isset($_SESSION['msg'])): 
        list($status, $text) = explode('|', $_SESSION['msg']);
        unset($_SESSION['msg']);
    ?>
        <div class="mb-6 p-4 rounded-2xl text-xs font-black uppercase tracking-widest text-center <?= $status == '1' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= $text ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-bullhorn text-6xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400 mb-1">Notificação em Tempo Real</p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter">B I P</h2>
            </div>
        </div>

        <div class="p-8">
            <form id="formBip" method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-[11px] font-black text-brand-blue uppercase tracking-widest mb-3 ml-1">
                        Acionar Responsável:
                    </label>
                    <div class="relative group mb-3">
                        <select id="selectBip" class="block w-full pl-6 pr-10 py-4 bg-gray-50 border-2 border-gray-100 text-brand-blue text-sm rounded-2xl focus:border-brand-blue outline-none transition-all cursor-pointer font-black italic uppercase">
                            <option value="" disabled selected>Escolher setor...</option>
                            <option value="313">Mecânica / Elétrica</option>
                            <option value="238">Líder de Produção</option>
                            <option value="426">Monitor de Qualidade</option>
                            <option value="105">Segurança do Trabalho</option>
                        </select>
                    </div>

                    <div id="tagsContainer" class="flex flex-wrap gap-2 px-1 min-h-[30px] mb-4"></div>
                    <input type="hidden" id="finalValues" name="bipar" required>
                </div>
                
                <div>
                    <label for="mensagem" class="block text-[11px] font-black text-brand-blue uppercase tracking-widest mb-3 ml-1">
                        Ocorrência / Detalhes:
                    </label>
                    <textarea id="mensagem" name="mensagem" rows="4" required maxlength="250"
                              placeholder="Descreva o problema..."
                              class="block w-full p-5 bg-gray-50 border-2 border-gray-100 rounded-[2rem] text-sm focus:border-brand-blue outline-none transition-all resize-none"></textarea>
                    
                    <div class="flex justify-between items-center mt-3 px-2">
                        <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest italic">Prioridade Normal</span>
                        <p class="text-[10px] text-brand-blue font-black font-mono bg-brand-blue/5 px-2 py-0.5 rounded-full" id="charCount">0/250</p>
                    </div>
                </div>
                
                <button type="submit" class="group relative w-full flex items-center justify-center gap-3 py-5 bg-brand-blue hover:bg-black text-white rounded-2xl shadow-xl transition-all active:scale-[0.98]">
                    <span class="font-black italic uppercase tracking-[0.2em] text-xs">Disparar Alerta BIP</span>
                    <i class="fas fa-paper-plane text-yellow-400"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <a href="index.php" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-all">
            <i class="fas fa-chevron-left text-[8px]"></i> Menu Principal
        </a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const components = {
        select: document.getElementById('selectBip'),
        container: document.getElementById('tagsContainer'),
        hiddenInput: document.getElementById('finalValues'),
        form: document.getElementById('formBip'),
        textarea: document.getElementById('mensagem'),
        counter: document.getElementById('charCount')
    };

    let selectedSectors = [];

    const renderTags = () => {
        components.container.innerHTML = '';
        selectedSectors.forEach(item => {
            const tag = document.createElement('div');
            tag.className = "flex items-center gap-2 bg-brand-blue text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase italic border-b-2 border-yellow-400 animate-fadeIn";
            tag.innerHTML = `
                <span>${item.name}</span>
                <button type="button" onclick="removeSector('${item.id}')" class="hover:text-yellow-400">
                    <i class="fas fa-times-circle"></i>
                </button>
            `;
            components.container.appendChild(tag);
        });
        components.hiddenInput.value = selectedSectors.map(s => s.id).join(',');
    };

    window.removeSector = (id) => {
        selectedSectors = selectedSectors.filter(s => s.id !== id);
        renderTags();
    };

    components.select.addEventListener('change', (e) => {
        const id = e.target.value;
        const name = e.target.options[e.target.selectedIndex].text;

        if (id && !selectedSectors.some(s => s.id === id)) {
            selectedSectors.push({ id, name });
            renderTags();
        }
        e.target.selectedIndex = 0;
    });

    components.textarea.addEventListener('input', (e) => {
        const len = e.target.value.length;
        components.counter.textContent = `${len}/250`;
        components.counter.classList.toggle('text-orange-500', len > 200);
    });

    components.form.addEventListener('submit', (e) => {
        if (selectedSectors.length === 0) {
            e.preventDefault();
            alert("Selecione ao menos um responsável.");
        }
    });
});
</script>

<?php include_once "footer.html"; ?>