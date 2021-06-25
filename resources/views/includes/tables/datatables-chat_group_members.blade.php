
<div class="row row-cols-lg-auto align-items-center">
    <div class="col ">
        <h3>Members</h3>
    </div>
{{--    <div class="col ">--}}
{{--        <label for="datatables-filter-role">Role</label>--}}
{{--        <select class="form-select" aria-label="Role" id="datatables-filter-role">--}}
{{--            <option value="">All</option>--}}
{{--            @foreach(config('chat-groups.chat-groups.roles') as $role => $title)--}}
{{--                <option value="{{$role}}">{{$title}}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}
{{--    <div class="col">--}}
{{--        <label for="datatables-filter-type">Type</label>--}}
{{--        <select class="form-select" aria-label="Type" id="datatables-filter-type">--}}
{{--            <option value="">All</option>--}}
{{--            @foreach(config('chat-groups.chat-groups.types') as $type => $title)--}}
{{--                <option value="{{$type}}">{{$title}}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}
    <div class="col ms-auto">
        <label for="datatables-filter-type">Search </label>
        <input class="form-control" list="datalistOptions" id="datatables-search-box" placeholder="Type to search...">
    </div>
</div>
<div class="row mt-2">
    <div class="col overflow-auto">
        <table class="table table-striped table-bordered" id="chat_group_list" style="width:100%">
            <thead>
            <tr>
{{--                <th scope="col">#</th>--}}
                <th scope="col">Username</th>
                <th scope="col">Role</th>
                <th scope="col">Joined</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


@push('scripts')
    <script>
        var chatGroupList = $('#chat_group_list').DataTable({
            "ajax": {
                url: "{{route('datatables_chat_groups_members_search',$chat_group->id)}}",
                type: 'POST',
                data: function (d) {
                    // d.datatable_filter_role = $('select#datatables-filter-role').val();
                    // d.datatables_filter_type = $('select#datatables-filter-type').val();
                    d._token = '{{ csrf_token() }}';
                }
            },
            columnDefs: [
                { targets: '_all', orderable: false, "searchable": false}
            ],
            "columns": [
                { "data": "username", searchable:true},
                { "data": "role", orderable: true},
                { "data": "joined", orderable: true},
            ],
            "order": [],
            "pageLength": 5,
            "processing": true,
            "serverSide": true,

            "ordering": true,
            "searching": true,
            "lengthChange": false,
            "bInfo" : false,
            "bFilter":false,
            "sDom":"ltipr",
            //"scrollX": true,
            //"deferRender": true,
            "language": {
                "paginate": {
                    "first":      "Sākums",
                    "last":       "Beigas",
                    "next":       ">",
                    "previous":   "<"
                },
            },

        });

        $('select#datatables-filter-role,select#datatables-filter-type').on('change', function(e) {
            chatGroupList.draw();
            e.preventDefault();
        });
        var search_time_out;

        $('#datatables-search-box').on('input',function(){

            let search_string = $(this).val();
            clearTimeout(search_time_out);

            search_time_out = setTimeout(function(){
                chatGroupList.search(search_string).draw() ;
            }, 1000);

        });
    </script>
@endpush
