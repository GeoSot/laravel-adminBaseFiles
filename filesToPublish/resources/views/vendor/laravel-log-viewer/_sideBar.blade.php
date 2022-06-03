<div class="adminSidebar navbar   h-100  bg-admin navbar-dark px-0 align-items-start">
    <section class="navbar-nav sidebar  flex-fill ">
        <ul id="menu" class="page-sidebar-menu flex-column  nav">
            @foreach($folders as $key => $fullFolder)
                @php($folder= \Illuminate\Support\Str::afterLast($fullFolder, DIRECTORY_SEPARATOR))
                <li class="nav-item">
                    <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                       {{--data-toggle="collapse" data-target="#collapse_{{$folder}}" role="button" aria-expanded="false" aria-controls="collapse_{{$folder}}"--}}
                       class="px-3 nav-link d-flex align-items-center small  @if ($isActive=$current_folder == $folder) active @endif">
                        <span class="title ">{{$folder}}</span>
                        <span class="fas arrow-after ml-auto pl-2 {!! $isActive?"fa-folder-open":"fa-folder" !!}"></span>
                    </a>
                    <ul id="collapse_{{$folder}}" class="sub-menu flex-column list-unstyled collapse @if ($isActive) show @endif">
                        @foreach($folder_files as $file)
                            <li class="pl-3 nav-item  ">
                                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                                   class="small nav-link px-3 inner-level-link d-flex align-items-center @if ($current_file == $file) active @endif">
                                    {{$file}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
            @foreach(glob($storage_path.'/*.log') as $fullFile)
                @php($file=\Illuminate\Support\Str::afterLast($fullFile, DIRECTORY_SEPARATOR))
                <li class="nav-item">
                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                       class="small nav-link px-3 inner-level-link d-flex align-items-center @if ($current_file == $file) active @endif">
                        {{$file}}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
</div>