<?php use function Tamtamchik\SimpleFlash\flash;
$this->layout('layout', ['title' => 'Безопаность', 'viewUname' => $uname, 'viewUid' => $uid]);?>

        <?php echo flash()->display(); ?>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-lock'></i> Безопасность
            </h1>

        </div>
        <form action="/secform" method="POST">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Обновление эл. адреса и пароля</h2>
                            </div>
                            <div class="panel-content">
                                <!-- id -->
                                <input type="hidden" id="simpleinput" name="id" value="<?=$viewUser[0]['id'] ?>">

                                <!-- email -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Email</label>
                                    <input type="email" id="simpleinput" name="email" class="form-control" value="<?=$viewUser[0]['email'] ?>" required>
                                </div>

                                <!-- new password -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Новый пароль</label>
                                    <input type="password" id="simpleinput" name="newPassword" class="form-control">
                                </div>

                                <!-- new password confirmation-->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Подтверждение нового пароля</label>
                                    <input type="password" id="simpleinput" name="newPasswordConfirm" class="form-control">
                                </div>

                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button type="submit" class="btn btn-warning">Изменить</button>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
        
