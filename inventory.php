<?php include_once "header.html"; ?>

<main class="flex-1 p-4 md:p-8 max-w-xl mx-auto w-full animate-fadeIn">
    
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-barcode text-6xl"></i>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl font-black italic uppercase tracking-tighter flex items-center gap-3">
                    <i class="fas fa-boxes-stacked"></i> Inventário
                </h2>
            </div>
        </div>

        <form id="formInventario" action="#" method="POST" class="p-8 space-y-8">
            
            <div class="bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 group focus-within:border-brand-blue transition-all">
                <label for="linha" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">
                    Localização do Inventário
                </label>
                <div class="relative">
                    <select id="linha" name="linha" required 
                            class="block w-full bg-white border-2 border-gray-100 rounded-2xl px-4 py-4 text-lg font-black text-brand-blue focus:ring-0 focus:border-brand-blue outline-none transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Selecione a Linha...</option>
                        <?php foreach(range('N', 'T') as $letra): ?>
                            <option value="<?= $letra ?>">LINHA <?= $letra ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-brand-blue pointer-events-none"></i>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cavidade</label>
                    <input type="text" id="cavidade" name="cavidade" required placeholder="Ex: C01"
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-4 font-black text-brand-blue placeholder:text-gray-300 focus:bg-white focus:border-brand-blue outline-none transition-all uppercase">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">GTCODE</label>
                    <input type="text" id="gtcode" name="gtcode" required placeholder="000000"
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-4 font-black text-brand-blue placeholder:text-gray-300 focus:bg-white focus:border-brand-blue outline-none transition-all uppercase">
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl border-2 border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1">Suporte / Carregador</p>
                        <span class="text-xs text-brand-blue font-bold italic">Quantidade Total</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="step('qtd_suporte', -1)" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-brand-blue hover:bg-brand-blue hover:text-white transition-all shadow-sm active:scale-90">-</button>
                        <input type="number" id="qtd_suporte" name="qtd_suporte" value="0" min="0" required 
                               class="w-16 text-center bg-transparent border-none font-black text-xl text-brand-blue focus:ring-0">
                        <button type="button" onclick="step('qtd_suporte', 1)" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-brand-blue hover:bg-brand-blue hover:text-white transition-all shadow-sm active:scale-90">+</button>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl border-2 border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1">Frente Prensa</p>
                        <span class="text-xs text-brand-blue font-bold italic">Em operação</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="step('qtd_prensa', -1)" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-brand-blue hover:bg-brand-blue hover:text-white transition-all shadow-sm active:scale-90">-</button>
                        <input type="number" id="qtd_prensa" name="qtd_prensa" value="0" min="0" required 
                               class="w-16 text-center bg-transparent border-none font-black text-xl text-brand-blue focus:ring-0">
                        <button type="button" onclick="step('qtd_prensa', 1)" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-brand-blue hover:bg-brand-blue hover:text-white transition-all shadow-sm active:scale-90">+</button>
                    </div>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full bg-brand-blue hover:bg-black text-white py-5 rounded-2xl shadow-xl shadow-blue-900/20 text-xs font-black uppercase italic tracking-[0.2em] flex justify-center items-center gap-3 transition-all active:scale-[0.98] group">
                <i class="fas fa-check-circle group-hover:rotate-12 transition-transform"></i> 
                Confirmar Registro
            </button>
        </form>
    </div>

    <div class="mt-8 text-center">
        <a href="index.php" 
           class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-colors group">
            <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i> 
            Voltar ao Dashboard
        </a>
    </div>

</main>

<script>
function step(id, value) {
    const input = document.getElementById(id);
    const newValue = parseInt(input.value) + value;
    if (newValue >= 0) input.value = newValue;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formInventario');
    const selectLinha = document.getElementById('linha');
    const inputs = {
        cavidade: document.getElementById('cavidade'),
        gtcode: document.getElementById('gtcode'),
        suporte: document.getElementById('qtd_suporte'),
        prensa: document.getElementById('qtd_prensa')
    };

    const storedLinha = localStorage.getItem('inventario_linha');
    if (storedLinha) selectLinha.value = storedLinha;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        localStorage.setItem('inventario_linha', selectLinha.value);

        if (typeof mostrarSucesso === "function") {
            mostrarSucesso();
        }

        inputs.cavidade.value = '';
        inputs.gtcode.value = '';
        inputs.suporte.value = '0';
        inputs.prensa.value = '0';
        inputs.cavidade.focus(); 
    });

    [inputs.cavidade, inputs.gtcode].forEach(el => {
        el.addEventListener('input', () => el.value = el.value.toUpperCase());
    });

    inputs.cavidade.focus();
});
</script>

<?php include_once "footer.html"; ?>