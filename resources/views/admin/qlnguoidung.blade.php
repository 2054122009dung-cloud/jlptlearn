<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="header">
        <span>JLPT Practice - Dashboard</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="content-header container-1200">
        <h1>Quản lý người dùng</h1>
        <ol class="breadcrumb">
            <p><a href="/jlpt">Trang chủ</a></p>
        </ol>
    </div>

    <section class="content container-1200">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Danh sách người dùng</h3>
                <button class="btn btn-primary show-popup-btn"
                        data-action="{{ route('admin.qlnguoidung.store') }}"
                        data-method="POST">
                    <i class="fas fa-plus"></i> Thêm người dùng
                </button>
            </div>

            <div class="card-body">
                @foreach(['success' => 'Thành công!', 'error' => 'Lỗi!'] as $key => $title)
                    @if(session($key))
                        <div class="alert alert-{{ $key == 'success' ? 'success' : 'danger' }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5><i class="icon fas fa-{{ $key == 'success' ? 'check' : 'ban' }}"></i> {{ $title }}</h5>
                            {{ session($key) }}
                        </div>
                    @endif
                @endforeach

                <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cập nhật</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user['MaNguoiDung'] }}</td>
                            <td>{{ $user['username'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                @foreach($user['roles'] as $role)
                                    {{ $role }}<br>
                                @endforeach
                            </td>
                            <td>{{ isset($user['create_at']) && is_object($user['create_at']) ? $user['create_at']->format('d/m/Y H:i') : ($user['create_at'] ?? 'N/A') }}</td>
                            <td>{{ isset($user['updated_at']) && is_object($user['updated_at']) ? $user['updated_at']->format('d/m/Y H:i') : ($user['updated_at'] ?? 'N/A') }}</td>
                            <td>
                                <button class="btn btn-info btn-sm show-popup-btn"
                                        data-action="{{ route('admin.qlnguoidung.update', $user['MaNguoiDung']) }}"
                                        data-method="PUT"
                                        data-user='@json($user)'>
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn btn-danger btn-sm show-popup-btn"
                                        data-action="{{ route('admin.qlnguoidung.destroy', $user['MaNguoiDung']) }}"
                                        data-method="DELETE"
                                        data-id="{{ $user['MaNguoiDung'] }}">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Popup Form -->
    <div id="popup-overlay" class="popup-overlay">
        <div class="popup-container">
            <div class="popup-header">
                <h4 id="popup-title">Thêm người dùng mới</h4>
                <button class="popup-close">&times;</button>
            </div>
            <!-- Chỉ có 1 form duy nhất -->
            <form id="user-form" method="POST">
                @csrf
                <div id="form-method"></div>
                <!-- Input ẩn chứa MaNguoiDung khi cập nhật -->
                <input type="hidden" id="user-id" name="MaNguoiDung">
                <div class="registration-container">
                    <h2 id="form-heading">Thêm người dùng mới</h2>

                    <div class="form-group">
                        <label for="username">Tên người dùng:</label>
                        <input type="text" id="username" name="username" required>
                        @error('username')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                        @error('email')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" id="password-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" id="password" name="password">
                        @error('password')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" id="password-confirmation-group">
                        <label for="password_confirmation">Nhập lại mật khẩu:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                    </div>

                    <!-- Thêm phần chọn vai trò -->
                    <div class="form-group">
                        <label for="vaitro">Vai Trò:</label>
                        <select id="vaitro" name="vaitro" class="form-control" required>
                            <option value="">Chọn vai trò</option>
                            @foreach($vaiTros as $vaiTro)
                                <option value="{{ $vaiTro->MaVaiTro }}">{{ $vaiTro->TenVaiTro }}</option>
                            @endforeach
                        </select>
                        @error('vaitro')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn-register">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#users-table").DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json" }
            });

            $('.show-popup-btn').on('click', function() {
                let data = $(this).data();
                $('#user-form').attr('action', data.action);
                // Thêm method spoofing nếu không phải POST
                if(data.method !== 'POST'){
                    $('#form-method').html(`@method('${data.method}')`);
                    $('#form-heading').text('Chỉnh sửa người dùng');
                } else {
                    $('#form-method').html('');
                    $('#form-heading').text('Thêm người dùng mới');
                }
                // Ẩn hiện nhóm mật khẩu: đối với update, có thể không cần cập nhật mật khẩu
                $('#password-group, #password-confirmation-group').toggle(data.method === 'POST');

                if (data.method !== 'POST') {
                    let user = data.user;
                    $('#user-id').val(user.MaNguoiDung);
                    $('#username').val(user.username);
                    $('#email').val(user.email);
                    // Giả sử user.roles là mảng, chọn vai trò đầu tiên nếu có
                    if(user.roles && user.roles.length > 0){
                        $('#vaitro').val(user.roles[0]);
                    } else {
                        $('#vaitro').val('');
                    }
                } else {
                    // Reset form khi thêm mới
                    $('#user-id').val('');
                    $('#username').val('');
                    $('#email').val('');
                    $('#password').val('');
                    $('#password_confirmation').val('');
                    $('#vaitro').val('');
                }

                $('#popup-overlay').addClass('popup-show');
            });

            $('.popup-close, #popup-overlay').on('click', function(e) {
                if (e.target === this) $('#popup-overlay').removeClass('popup-show');
            });
        });
    </script>
</body>
</html>
