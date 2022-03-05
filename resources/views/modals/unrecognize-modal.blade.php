<div class="modal fade unrecognize-modal" tabindex="-1" role="dialog" id="unrecognizeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Привязка нераспознанного ИГ</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left mt10 mr10"><strong>Поиск ИГ по:</strong></div>
                            <a target="_blank" class="btn btn-primary pull-left mr10 search-document">Паспорту</a>
                            <a target="_blank" class="btn btn-primary pull-left mr10 search-inn">ИНН</a>
                            <a target="_blank" class="btn btn-primary pull-left mr10 search-name">ФИО</a>
                        </div>
                    </div>
                    <div class="row mt20">
                        <div class="form-group col-md-6">
                            <label class="control-label">ID ИГ</label>
                            <input class="form-control ig-id" autofocus="autofocus" name="ig_id" type="text">
                            <button class="btn btn-success btn-link-submit mt25 pull-right" type="submit">Привязать</button>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label mt10">Фамилия</label>
                            <input class="form-control ig-id" name="surname" type="text">

                            <label class="control-label">Имя</label>
                            <input class="form-control ig-id" name="name" type="text">

                            <label class="control-label mt10">Отчество</label>
                            <input class="form-control ig-id" name="middle_name" type="text">

                            <label class="control-label mt10">Серия документа</label>
                            <input class="form-control ig-id" name="document_series" type="text">

                            <label class="control-label mt10">Номер документа</label>
                            <input class="form-control ig-id" name="document_number" type="text">

                            <label class="control-label mt10">ИНН</label>
                            <input class="form-control ig-id" name="inn" type="text">

                            <a class="btn btn-primary mt25 pull-right btn-create">Создать нового ИГ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>