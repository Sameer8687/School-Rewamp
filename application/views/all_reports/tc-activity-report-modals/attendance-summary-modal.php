<!-- Modal -->
<div class="modal fade" id="attendanceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl rounded-0">
        <div class="modal-content rounded-0">
            <div class="modal-header p-2">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Activity Attendance Summary Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearModal('attendance-tbody')"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teacher ID</th>
                            <th>Teacher Name</th>
                            <th class="text-center">Attendance Date</th>
                            <th class="text-center">Class</th>
                            <th class="text-center">Total Present</th>
                            <th class="text-center">Total Absent</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>