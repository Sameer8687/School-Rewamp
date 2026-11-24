<!-- Modal -->
<div class="modal fade" id="markDetailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl rounded-0">
        <div class="modal-content rounded-0">
            <div class="modal-header p-2">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Marks Activity Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModal('mark-detail-tbody')"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Teacher ID</th>
                            <th class="">Student Name</th>
                            <th class="text-center">Class</th>
                            <th class="">Exam Name</th>
                            <th class="">Subject</th>
                            <th class="text-center">Total Marks</th>
                            <th class="text-center">Obtain Marks</th>
                        </tr>
                    </thead>
                    <tbody id="mark-detail-tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>