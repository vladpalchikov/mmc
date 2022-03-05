<div class="modal fade new-foreigner-modal" tabindex="-1" role="dialog" id="newApplication">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Проверка гражданина</h4>
            </div>
            <div class="modal-body">
                <div class="row modal-new-foreigner">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <p>Укажите паспортные данные для проверки по базе зарегистрированных граждан</p>
                        </div>
                    </div>
                    <div class="col-md-12 document-number-error hide">
                        <div class="alert alert-danger">
                            <p>Поле "Номер документа" обязательно для заполнения</p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="document" class="control-label">Серия</label>
                        <input class="form-control" autofocus="autofocus" name="document_series" type="text">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="document" class="control-label required">Номер</label>
                        <input class="form-control" required="required" name="document_number" type="text">
                    </div>
                </div>

                <div class="modal-new-foreigner-table hide">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover modal-new-foreigner-results">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Номер</th>
                                    <th>Клиент</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-new-foreigner">
              <button class="btn btn-success" type="submit">Продолжить</button>
            </div>
        </div>
    </div>
</div>
