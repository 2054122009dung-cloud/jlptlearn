<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\LichSuSai;

class QuizComponent extends Component
{
    public $questions = [];
    public $answers = [];
    public $currentQuestion = 0;
    public $score = 0;
    public $finished = false;
    public $de_thi;

    public function mount()
    {
        $path = resource_path('views/student/quiz.json');
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            $this->questions = $data['cau_hoi'] ?? [];
            shuffle($this->questions);

            // Kiểm tra và cung cấp giá trị mặc định nếu không có ma_cau_hoi
            foreach ($this->questions as $key => $question) {
                if (is_array($question)) {
                    // Kiểm tra xem mỗi câu hỏi có 'ma_cau_hoi' không, nếu không sẽ gán giá trị mặc định
                    $this->questions[$key]['ma_cau_hoi'] = $this->questions[$key]['ma_cau_hoi'] ?? 'default_' . $key;
                }
            }

            $this->de_thi = $data['de_thi'] ?? "Không có đề thi";
        }
    }

    public function selectAnswer($questionIndex, $answerId)
    {
        if (!$this->finished) {
            $question = $this->questions[$questionIndex];
            $correctAnswer = $question['dap_an_dung']; // Đáp án đúng (số ID)

            $this->answers[$questionIndex] = $answerId;  // Lưu lựa chọn của người dùng

            if ($answerId == $correctAnswer) {
                $this->removeWrongAnswer($questionIndex); // Nếu trả lời đúng, giảm số lỗi (hoặc xóa lịch sử)
                $this->score++;
                // Nếu cần cập nhật đáp án đúng vào lịch sử, có thể gọi updateCorrectAnswer ở đây
            } else {
                // Lấy đáp án sai từ mảng đáp án dựa trên lựa chọn của người dùng
                $incorrectAnswer = collect($question['dap_an'])->firstWhere('id', $answerId)['noi_dung'];
                // Gọi hàm lưu lỗi với tham số questionIndex, đáp án sai và đáp án đúng (nội dung của đáp án đúng)
                $this->saveWrongAnswer($questionIndex, $incorrectAnswer, $question['dap_an_dung']);
            }

            if ($questionIndex + 1 < count($this->questions)) {
                $this->currentQuestion++;
            } else {
                $this->finished = true;
            }
        }
    }
    private function saveWrongAnswer($questionIndex, $incorrectAnswer, $correctAnswerContent)
    {
        $maNguoiDung = auth()->user()->MaNguoiDung;
        // Lấy ma_cau_hoi từ câu hỏi hiện tại
        $maCauHoi = $this->questions[$questionIndex]['ma_cau_hoi'];

        // Tạo mã lỗi cố định bao gồm MaNguoiDung và ma_cau_hoi
        $maLoi = $maNguoiDung . '_' . $maCauHoi;  // Dùng ma_cau_hoi thay vì questionIndex

        // Kiểm tra nếu bản ghi lịch sử lỗi đã tồn tại
        $lichSuSai = LichSuSai::firstOrNew(
            ['MaNguoiDung' => $maNguoiDung, 'MaLoi' => $maLoi]
        );

        if (!$lichSuSai->exists) {
            // Nếu chưa tồn tại bản ghi lỗi, tạo mới và gán số lỗi là 1
            $lichSuSai->DapAnSai = $incorrectAnswer;      // Lưu đáp án người dùng đã chọn (sai)
            $lichSuSai->DapAnDung = $correctAnswerContent;  // Lưu đáp án đúng (để đối chiếu)
            $lichSuSai->SoLoiSai = 1;
        } else {
            // Nếu bản ghi đã tồn tại, kiểm tra nếu đáp án sai giống nhau, tăng số lỗi
            if ($lichSuSai->DapAnSai === $incorrectAnswer) {
                $lichSuSai->SoLoiSai = $lichSuSai->SoLoiSai + 1;
            } else {
                // Nếu đáp án sai khác, cập nhật lại đáp án sai và giữ số lỗi là 1
                $lichSuSai->DapAnSai = $incorrectAnswer;
                $lichSuSai->SoLoiSai = 1; // Reset số lỗi về 1 vì đáp án sai đã thay đổi
            }
        }

        // Cập nhật thời gian làm bài cuối
        $lichSuSai->LanLamBaiCuoi = now();
        $lichSuSai->save();
    }

    private function removeWrongAnswer($questionIndex)
    {
        $maNguoiDung = auth()->user()->MaNguoiDung;
        // Lấy ma_cau_hoi từ câu hỏi hiện tại
        $maCauHoi = $this->questions[$questionIndex]['ma_cau_hoi'];

        // Tạo mã lỗi cố định bao gồm MaNguoiDung và ma_cau_hoi
        $maLoi = $maNguoiDung . '_' . $maCauHoi;  // Dùng ma_cau_hoi thay vì questionIndex

        $lichSu = LichSuSai::where('MaNguoiDung', $maNguoiDung)
                            ->where('MaLoi', $maLoi)
                            ->first();

        if ($lichSu) {
            // Nếu số lỗi là 1, xóa bản ghi lỗi
            if ($lichSu->SoLoiSai == 1) {
                $lichSu->delete();
            } else {
                // Nếu có nhiều lần sai, giảm số lỗi xuống 1 đơn vị
                $lichSu->SoLoiSai = $lichSu->SoLoiSai - 1;
                $lichSu->save();
            }
        }
    }




    // Cập nhật phương thức update đáp án đúng khi người dùng trả lời đúng
    private function updateCorrectAnswer($questionIndex, $correctAnswerContent)
    {
        $maNguoiDung = auth()->user()->MaNguoiDung;
        $maLoi = LichSuSai::generateMaLoi();

        $lichSuSai = LichSuSai::firstOrNew(
            ['MaNguoiDung' => $maNguoiDung, 'MaLoi' => $maLoi]
        );

        $lichSuSai->DapAnDung = $correctAnswerContent;
        $lichSuSai->save();
    }


    // public function render()
    // {
    //     return view('livewire.quiz-component', ['de_thi' => $this->de_thi]);
    // }
    public function render()
    {
        return view('livewire.quiz-component');
    }
}
