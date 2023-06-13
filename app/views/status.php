<?php use function Tamtamchik\SimpleFlash\flash;
$this->layout('layout', ['title' => 'Установить статус', 'viewUname' => $uname, 'viewUid' => $uid]);?>

        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>
        <form action="/statusform" method="POST">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса для <?=$viewUser[0]['username'] ?></h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- id -->
                                        <input type="hidden" name="id" value="<?=$viewUser[0]['id'] ?>">

                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="example-select">Выберите статус</label>
                                            <select name="status" class="form-control" id="example-select">
                                                <?php foreach($viewStatus as $status): ?>
                                                <?php if($viewUser[0]['user_status'] == $status['engname']): ?>
                                                <option selected value="<?=$status['engname'] ?>"><?=$status['name'] ?></option>
                                                <?php else: ?>
                                                <option value="<?=$status['engname'] ?>"><?=$status['name'] ?></option>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-warning">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
