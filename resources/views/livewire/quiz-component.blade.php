<div class="p-5">
    <div class="quiz-container">
        <h2 class="text-2xl font-bold mb-3">Đề thi: {{ $de_thi }}</h2>

        @if(!$finished)
            <div class="question mb-4">
                <p class="font-bold text-lg">{{ $questions[$currentQuestion]['de_bai'] }}</p>
            </div>

            <div class="flex space-x-4">
                @foreach($questions[$currentQuestion]['dap_an'] as $index => $dap_an)
                    <button wire:click="selectAnswer({{ $currentQuestion }}, {{ $dap_an['id'] }})"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-200 flex items-center justify-center w-20">
                        <span class="font-bold mr-2">{{ $index + 1 }}.</span> {{ $dap_an['noi_dung'] }}
                    </button>
                @endforeach
            </div>
        @else
            <div class="result">
                <h2 class="text-2xl font-bold text-green-600">Kết quả</h2>
                <p>Bạn đã trả lời đúng {{ $score }} / {{ count($questions) }} câu.</p>
            </div>
        @endif
    </div>


</div>
