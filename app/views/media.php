<?php use function Tamtamchik\SimpleFlash\flash;
$this->layout('layout', ['title' => 'Загрузить аватар', 'viewUname' => $uname, 'viewUid' => $uid]);?>

        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-image'></i> Загрузить аватар
            </h1>
        </div>
        <form action="/umediaform" enctype="multipart/form-data" method="POST">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Текущий аватар <?=$viewUser[0]['username'] ?></h2>
                            </div>
                            <div class="panel-content">
                                <input type="hidden" id="example-fileinput" name="id" value="<?=$viewUser[0]['id'] ?>">
                                <input type="hidden" id="example-fileinput" name="img" value="<?=$viewUser[0]['img'] ?>">
                                <div class="form-group">
                                    <?php if(!empty($viewUser[0]['img'])): ?>
                                    <img src="<?=$viewUser[0]['img'] ?>" alt="" class="img-responsive" width="200">
                                    <?php else: ?>
                                    <img src="img/demo/avatars/avatar-m.png" alt="" class="img-responsive" width="200">
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="example-fileinput">Выберите аватар</label>
                                    <input type="file" id="example-fileinput" name="file" class="form-control-file">
                                </div>

                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button type="submit" class="btn btn-warning">Загрузить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
