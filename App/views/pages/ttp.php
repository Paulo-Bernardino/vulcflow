<div class="hidden bg-red-600 bg-green-600 bg-gray-100 bg-black/50 border-red-500 bg-red-50 text-red-600"></div>

<div class="p-4 md:p-8 max-w-lg mx-auto w-full animate-fadeIn" x-data="checkTTP()" x-cloak>
    
    <div x-show="showOutOfSpecModal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999; display: none;">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="showOutOfSpecModal = false"></div>
        <div x-show="showOutOfSpecModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="relative bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl text-center border-4 border-red-500">
            <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-2xl font-black text-brand-blue uppercase italic mb-2">Atenção!</h3>
            <p class="text-gray-600 font-bold text-xs mb-8 uppercase leading-relaxed">
                <span class="text-red-600 font-black" x-text="getOutMessage()"></span> FORA DA ESPECIFICAÇÃO.<br>
                ABRIR O.S. E BIPAR EQUIPE?
            </p>
            <div class="flex gap-3">
                <button type="button" @click="saveData(true)" class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-black uppercase italic text-[10px] shadow-lg active:scale-95">SIM (Bipar)</button>
                <button type="button" @click="saveData(false)" class="flex-1 bg-red-600 text-white py-4 rounded-2xl font-black uppercase italic text-[10px] shadow-lg active:scale-95">NÃO</button>
            </div>
        </div>
    </div>

    <div x-show="isProcessing" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 10000; display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div x-show="isProcessing" x-transition:enter="transition ease-out duration-300"
            class="relative bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl text-center border-4 border-brand-blue/10">
            
            <div class="mb-6 relative inline-block">
                <i class="fas fa-circle-notch fa-spin text-brand-blue text-5xl"></i>
            </div>
            
            <h3 class="text-xl font-black text-brand-blue uppercase italic mb-6">Processando...</h3>

            <div class="space-y-3 inline-block text-left w-full px-4">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center gap-4 transition-all duration-500"
                         :class="statusStep >= index ? 'opacity-100' : 'opacity-30'">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center transition-colors duration-500"
                             :class="statusStep > index ? 'bg-green-500' : 'bg-gray-100'">
                            <i class="fas fa-check text-[10px] text-white" x-show="statusStep > index"></i>
                            <div class="w-2 h-2 bg-brand-blue rounded-full" x-show="statusStep <= index"></div>
                        </div>
                        <p class="font-black uppercase italic tracking-tighter text-[11px]" 
                           :class="statusStep == index ? 'text-brand-blue animate-pulse' : 'text-gray-400'"
                           x-text="step"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div x-show="showSuccessModal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9998; display: none;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div x-show="showSuccessModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="relative bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl text-center">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="text-2xl font-black text-brand-blue uppercase italic mb-2">Sucesso!</h3>
            <p class="text-gray-500 font-bold text-sm mb-8 uppercase tracking-widest">Checklist TTP registrado!</p>
            <button type="button" @click="window.location.reload()" class="w-full bg-brand-blue text-white py-4 rounded-2xl font-black uppercase italic tracking-widest shadow-lg">OK</button>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10"><i class="fas fa-gauge-high text-6xl"></i></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400 mb-1">Controle de Processo</p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter flex items-center gap-3">
                    <i class="fas fa-temperature-half"></i> Checklist TTP
                </h2>
            </div>
        </div>

        <div class="p-8">
            <form @submit.prevent="validateAndSubmit" class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 relative">
                    <span class="absolute -top-3 left-6 bg-white px-3 py-1 text-[9px] font-black text-brand-blue uppercase tracking-widest border-2 border-gray-100 rounded-full">
                        <i class="fas fa-industry mr-1"></i> Identificação
                    </span>
                    <div class="grid grid-cols-1 gap-4 mt-2">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Linha de Produção</label>
                            <select x-model="formData.linha" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none cursor-pointer">
                                <template x-for="l in ['N', 'O', 'P', 'Q', 'R', 'S', 'T']">
                                    <option :value="l" x-text="'Linha ' + l"></option>
                                </template>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Cavidade</label>
                                <input type="text" x-model="formData.cavidade" @blur="fetchGTCode()" placeholder="EX: 01" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none uppercase">
                            </div>
                            <div class="flex items-center justify-center bg-brand-blue/5 rounded-2xl border-2 border-brand-blue/10">
                                <div class="text-center">
                                    <p class="text-[8px] font-black text-brand-blue/40 uppercase">GTCODE</p>
                                    <p class="text-xs font-black text-brand-blue" x-text="gtCode || '---'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Tempo Espec.</label>
                            <input type="number" step="0.1" x-model="formData.tempoEspec" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Tempo Obtido</label>
                            <input type="number" step="0.1" x-model="formData.tempoObtido" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Temp. Espec.</label>
                            <input type="number" step="0.1" x-model="formData.tempEspec" 
                                placeholder="Ex: 150"
                                class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Temp. Obtida</label>
                            <input type="number" step="0.1" x-model="formData.tempObtido" 
                                :class="isOutOfSpec(formData.tempObtido, formData.tempEspec, 1) ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-100'"
                                class="block w-full px-5 py-4 border-2 rounded-2xl text-sm font-black outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Pressão 200 (± 5)</label>
                            <input type="number" x-model="formData.pressao200" 
                                :class="isOutOfSpec(formData.pressao200, 200, 5) ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-100'"
                                class="block w-full px-5 py-4 border-2 rounded-2xl text-sm font-black outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-brand-blue uppercase mb-1 ml-1">Pressão 400 (± 10)</label>
                            <input type="number" x-model="formData.pressao400" 
                                :class="isOutOfSpec(formData.pressao400, 400, 10) ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-100'"
                                class="block w-full px-5 py-4 border-2 rounded-2xl text-sm font-black outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-brand-blue uppercase text-center mb-2">Extensão de Cura?</label>
                        <div class="flex gap-2">
                            <button type="button" @click="formData.extensao = 'SIM'" 
                                :class="formData.extensao === 'SIM' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-400'" 
                                class="flex-1 py-4 rounded-2xl font-black text-xs uppercase italic transition-all">Sim</button>
                            <button type="button" @click="formData.extensao = 'NÃO'" 
                                :class="formData.extensao === 'NÃO' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-400'" 
                                class="flex-1 py-4 rounded-2xl font-black text-xs uppercase italic transition-all">Não</button>
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="isProcessing" class="w-full flex items-center justify-center gap-3 py-5 bg-brand-blue hover:bg-black text-white rounded-2xl shadow-xl transition-all active:scale-95">
                    <i x-show="isProcessing" class="fas fa-spinner fa-spin"></i>
                    <span class="font-black italic uppercase tracking-[0.2em] text-xs" x-text="isProcessing ? 'Gravando...' : 'Finalizar Checklist TTP'"></span>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <a href="?page=home" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
            <i class="fas fa-chevron-left text-[8px]"></i> Menu Principal
        </a>
    </div>
</div>

<script>
function checkTTP() {
    return {
        gtCode: '',
        showOutOfSpecModal: false,
        isProcessing: false,
        showSuccessModal: false,
        statusStep: 0,
        steps: [],
        formData: {
            linha: localStorage.getItem('ttp_linha') || 'N',
            cavidade: '',
            tempoEspec: '',
            tempoObtido: '',
            tempEspec: '',
            tempObtido: '',
            extensao: 'NÃO',
            pressao200: '',
            pressao400: ''
        },
        
        isOutOfSpec(val, target, tolerance) {
            if (!val || val === "" || !target || target === "") return false;
            let v = parseFloat(val);
            let t = parseFloat(target);
            return (v < (t - tolerance) || v > (t + tolerance));
        },

        getOutMessage() {
            let msg = [];
            if (this.isOutOfSpec(this.formData.tempObtido, this.formData.tempEspec, 1)) {
                msg.push("TEMPERATURA");
            }
            if (this.isOutOfSpec(this.formData.pressao200, 200, 5)) msg.push("P200");
            if (this.isOutOfSpec(this.formData.pressao400, 400, 10)) msg.push("P400");
            return msg.join(" e ");
        },

        async fetchGTCode() {
    if (!this.formData.cavidade) {
        this.gtCode = '';
        return;
    }

    try {
                const response = await fetch('?page=api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }, // Corrigido
                    body: JSON.stringify({
                        action: 'searchGTPress',
                        press: this.formData.cavidade
                    })
                });

                if (!response.ok) throw new Error('Erro na requisição');

                const data = await response.text();
                this.gtCode = data.trim() || 'NÃO ENCONTRADO';
            } catch (error) {
                console.error("Erro ao buscar GT:", error);
                this.gtCode = 'ERRO';
            }
        },

        validateAndSubmit() {
            const isTempOut = this.isOutOfSpec(this.formData.tempObtido, this.formData.tempEspec, 1);
            const isP200Out = this.isOutOfSpec(this.formData.pressao200, 200, 5);
            const isP400Out = this.isOutOfSpec(this.formData.pressao400, 400, 10);

            if (isTempOut || isP200Out || isP400Out) {
                this.showOutOfSpecModal = true;
            } else {
                this.saveData(false);
            }
        },

        async saveData(abrirOS) {
            this.showOutOfSpecModal = false;
            this.isProcessing = true;
            this.statusStep = 0;
            
            if (abrirOS) {
                this.steps = ["Bipando Equipe", "Abrindo O.S.", "Salvando Checklist"];
            } else {
                this.steps = ["Validando dados", "Salvando Checklist"];
            }

            for (let i = 0; i < this.steps.length; i++) {
                this.statusStep = i;
                await new Promise(r => setTimeout(r, 800));
            }
            
            this.statusStep = this.steps.length;
            await new Promise(r => setTimeout(r, 400));
            
            this.isProcessing = false;
            this.showSuccessModal = true;
            
            localStorage.setItem('ttp_linha', this.formData.linha);
        }
    }
}
</script>