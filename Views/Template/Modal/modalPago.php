<div class="modal fade" id="modalPago">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Continua con el pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table align-middle text-break">
                    <tbody id="listItem">
                        <form id="formSchedule">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="scheduleFirstname" class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="scheduleFirstname" name="scheduleFirstname" >
                                        <ul class="scheduleFirstname text-danger"></ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="schedulePhone" class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="schedulePhone" name="schedulePhone">
                                        <ul class="schedulePhone text-danger"></ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="scheduleEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="scheduleEmail" name="scheduleEmail" >
                                        <ul class="scheduleEmail text-danger"></ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="scheduleDate" class="form-label">Select date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="scheduleDate" name="scheduleDate">
                                        <ul class="scheduleDate text-danger"></ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="scheduleTime" class="form-label">Select time <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="scheduleTime" name="scheduleTime" ></select>
                                        <ul class="scheduleTime text-danger"></ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="scheduleService" class="form-label">Services <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="scheduleService" name="scheduleService" >
                                        </select>
                                        <ul class="scheduleService text-danger"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-bg-1" id="btnSchedule" >Schedule</button>
                                <button type="button" class="btn btn-bg-2 text-white" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>