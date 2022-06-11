@extends('layout.site')
@section('title', $meta['title'])
@section('keywords', $meta['keywords'])
@section('description', $meta['description'])
@section('name', $meta['name'])
@section('author', $meta['author'])
@section('twitter', $meta['twitter'])
@section('locale', $locale)
@section('url', $thisUrl)


@section('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">
    <style type="text/css">
        @font-face {
            font-family: Delicious;
            src: url('{{ config('app.url') }}fonts/UthmanicHafs1_Ver18.ttf');
        }
    </style>

@endsection


@section('right-button')
    <li>
        <a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right">
            <i class="material-icons">format_indent_increase</i>
        </a>
    </li>
@endsection


@section('search-desktop')
    <div class="header-search-wrapper hide-on-med-and-down input-field">
        <i class="material-icons">search</i>
        <input id="autocomplete-input" class="header-search-input z-depth-2 autocomplete" type="text" name="Search"
               placeholder="{{__('app.search_in_quran')}}" data-search="template-list">
        <ul class="search-list collection display-none"></ul>
    </div>
@endsection

@section('content')

    <div class="section mobile-section">

        <div class="row mobile-full">
            <div class="col s12 m12 l5">
                <div class="card">
                    <div id="verse-content" class="card-content">

                        <div style="margin-bottom:3.5rem">
                            <h5>{{__('app.chapter_name', ['chapter_id'=>$thisChapter->chapter_id, 'chapter' => $thisChapter->chapter_name])}}</h5>
                            <blockquote style="font-size: 12pt">
                                {{$thisVerse->verse_id}}. <span
                                    class="ayath-content">{{$thisVerse->verse_text}}</span>
                            </blockquote>
                        </div>

                        <div style="direction: rtl;">
                            <span lang="AR-SA"
                                  style="font-size:2em;font-family:&quot;KFGQPC HAFS Uthmanic Script&quot;">{{$thisVerseArabic->verse_text}}&nbsp;{{arabic_w2e($thisVerseArabic->verse_id)}}</span>
                            <br/>
                        </div>

                        <div>
                            <span
                                style="font-size: 9pt;">{{$thisVerseTransl->verse_id}}. {{$thisVerseTransl->verse_text}}</span>
                        </div>

                        <div style="margin-top:3em; text-align:right">

                            <button onclick="window.open('{{$paginationSide[1]['url']}}', '_self');"
                                @class([
                                  'disabled'=>$paginationSide[1]['disabled'],
                                  'waves-effect waves-light'=>!$paginationSide[1]['disabled'],
                                  'btn operation-btn' => true
                                ])
                            >
                                {{__('pagination.previous')}}
                                <i class="material-icons left">arrow_back_ios</i>
                            </button>
                            <button onclick="window.open('{{$paginationSide[2]['url']}}', '_self');"
                                @class([
                                  'disabled'=>$paginationSide[2]['disabled'],
                                  'waves-effect waves-light'=>!$paginationSide[2]['disabled'],
                                  'btn operation-btn' => true
                                ])>
                                {{__('pagination.next')}}
                                <i class="material-icons right">arrow_forward_ios</i>
                            </button>

                            <button
                                onclick="openShareModal({{$thisVerse->verse_id}}, '{{__('app.chapter_verse', ['chapter' => $thisChapter->chapter_name, 'verse' => $thisVerse->verse_id])}}')"
                                class="btn waves-effect waves-light operation-btn">
                                {{__('app.share')}}
                                <i class="material-icons right">share</i>
                            </button>
                            <button
                                onclick="copyText({{$thisVerse->verse_id}}, '{{__('app.chapter_verse', ['chapter' => $thisChapter->chapter_name, 'verse' => $thisVerse->verse_id])}}')"
                                class="btn waves-effect waves-light operation-btn">
                                {{__('app.copy')}}
                                <i class="material-icons right">content_copy</i>
                            </button>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l7">
                <div class="card">
                    <div id="vocable-content" class="card-content">
                        <table class="responsive-table">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>{{ __('transl.arabic') }}</td>
                                @foreach($selectedVocableLangKeys as $key)
                                    <td>{{ __('transl.'.$key) }}</td>
                                @endforeach
                                <td>{{__('app.root')}}</td>
                                {{-- <td>Latin</td> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vocables as $vocable)
                                <tr>
                                    <td>{{ $vocable->sort_id }}</td>
                                    <td>
                                        <span lang="AR-SA"
                                              style="font-size:16pt;font-family:&quot;KFGQPC HAFS Uthmanic Script&quot;">{{ $vocable->arabic }}</span>
                                    </td>
                                    @foreach($selectedVocableLangKeys as $key)
                                        <td>{{ $vocable->{$key} }}</td>
                                    @endforeach
                                    <td>
                                        <span lang="AR-SA"
                                              style="font-size:14pt;font-family:&quot;KFGQPC HAFS Uthmanic Script&quot;">{{ $vocable->root_text }}</span>
                                    </td>
                                    {{-- <td>{{ make_slug($vocable->root_text) }}</td> --}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mobile-full">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <table class="trans-verses">
                            <tbody>
                            @foreach($selectedVerses as $verse)
                                <tr>
                                    <div class="row">
                                        <td>
                                            <div style="padding: 0" class="col s12 m3 l2">
                                                <i>{{$verse->translator}}</i>
                                            </div>
                                            <div style="padding: 0; font-weight: bold;" class="col s12 m9 l10">
                                                {{$verse->verse_text}}
                                            </div>
                                        </td>
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div>

                            <div class="row">
                                <div class="col s12 m2 l3">
                                </div>
                                <div class="col s12 m8 l6" style="text-align: center;">
                                    <ul class="pagination">
                                        <li @class(['disabled'=>$paginationSide[0]['disabled'], 'waves-effect' => !$paginationSide[0]['disabled']])>
                                            <a href="{{$paginationSide[0]['url']}}"><i class="material-icons">keyboard_double_arrow_left</i></a>
                                        </li>
                                        <li @class(['disabled'=>$paginationSide[1]['disabled'], 'waves-effect' => !$paginationSide[1]['disabled']])>
                                            <a href="{{$paginationSide[1]['url']}}"><i class="material-icons">chevron_left</i></a>
                                        </li>
                                        @foreach($pagination as $pagiantion)
                                            <li @class(['active' => $pagiantion['active'], 'waves-effect' => !$pagiantion['active']])>
                                                <a href="{{$pagiantion['url']}}">{{$pagiantion['label']}}</a>
                                            </li>
                                        @endforeach
                                        <li @class(['disabled'=>$paginationSide[2]['disabled'], 'waves-effect' => !$paginationSide[2]['disabled']])>
                                            <a href="{{$paginationSide[2]['url']}}"><i class="material-icons">chevron_right</i></a>
                                        </li>
                                        <li @class(['disabled'=>$paginationSide[3]['disabled'], 'waves-effect' => !$paginationSide[3]['disabled']])>
                                            <a href="{{$paginationSide[3]['url']}}"><i class="material-icons">keyboard_double_arrow_right</i></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col s12 m2 l3">

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <aside id="right-sidebar-nav">
        <div id="slide-out-right" class="slide-out-right-sidenav sidenav rightside-navigation">
            <div class="row">
                <div class="slide-out-right-title">
                    <div class="col s12 border-bottom-1 pb-0 pt-1">
                        <div class="row">
                            <div class="col s2 pr-0 center">
                                <i class="material-icons vertical-text-middle"><a href="#"
                                                                                  class="sidenav-close">clear</a></i>
                            </div>
                            <div class="col s10 pl-0">
                                <ul class="tabs">
                                    <li class="tab col s4 p-0">
                                        <a href="#messages" class="active">
                                            <span>{{__('app.means')}}</span>
                                        </a>
                                    </li>
                                    <li class="tab col s4 p-0">
                                        <a href="#settings">
                                            <span>{{__('app.vocable')}}</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <button onclick="applyChanges()" style="width: 90%; margin-left: 5%;"
                                    class="btn verse-option-button waves-effect waves-light">
                                {{__('app.apply')}}
                            </button>
                        </div>

                    </div>
                </div>
                <div class="slide-out-right-body row pl-3">
                    <div id="messages" class="col s12 pb-0">
                        <div class="collection border-none mb-0">
                            <input class="header-search-input mt-4 mb-2" type="text" name="Search"
                                   placeholder="Search Messages"/>
                            <ul id="transl-list" class="collection right-sidebar-chat p-0 mb-0">
                                @foreach ($translations as $translation)
                                    <li id="item-{{$translation->id}}" data-id="{{$translation->id}}"
                                        onclick="onClickTranslItem({{$translation->id}})"
                                        class="collection-item right-sidebar-chat-item sidenav-trigger display-flex avatar pl-5 pb-0">
                                        <div class="user-content">
                                            <h6 class="line-height-0">{{$translation->translator}}</h6>
                                            <p class="medium-small blue-grey-text text-lighten-3 pt-3">{{__('transl.'.$translation->lang_name)}}</p>
                                        </div>
                                        <span class="secondary-content medium-small">
                          <div class="right">
                              @if(in_array($translation->id, $selectedTranslations))
                                  <i style="color: #aa3691;" class="material-icons">done</i>
                              @else
                                  <i style="color: #aa3691; display:none" class="material-icons">done</i>
                              @endif
                          </div>
                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="settings" class="col s12">
                        <p class="setting-header mt-8 mb-3 ml-5 font-weight-900">Kuran Kelime Meali</p>
                        <ul id="vocable-lang-list" class="collection border-none">
                            @foreach($vocableLangList as $vocableLang)
                                <li data-key="{{$vocableLang['key']}}" class="collection-item border-none">
                                    <div class="m-0">
                                        <span>{{ $vocableLang['name'] }}</span>
                                        <div class="switch right">
                                            <label>
                                                @if(in_array($vocableLang['key'], $selectedVocableLangKeys))
                                                    <input checked type="checkbox"/>
                                                @else
                                                    <input type="checkbox"/>
                                                @endif
                                                <span class="lever"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                </div>

            </div>
        </div>


    </aside>

    <x-modal/>

@endsection

@section('footer')
    <script type="text/javascript">

        $(document).ready(function () {
            if (window.innerWidth > 976) {
                const vocableContentHeight = $('#vocable-content').height();
                const verseContentHeight = $('#verse-content').height();
                if (vocableContentHeight > verseContentHeight) {
                    $('#verse-content').height(vocableContentHeight);
                } else {
                    $('#vocable-content').height(verseContentHeight);
                }
            }
        });


        const onClickTranslItem = (id) => {
            const itemId = "item-" + id;
            $(`#${itemId}`).find('.material-icons').toggle();
        }

        const applyChanges = () => {
            var savedIds = [];
            $('#transl-list').each(function () {
                $(this).find('li').each(function () {
                    const current = $(this);
                    const icon = current.find('.material-icons')
                    if (icon.is(":visible")) {
                        const dataId = current.attr("data-id");
                        savedIds.push(dataId)
                    }
                });
            });

            var savedKeys = [];
            $('#vocable-lang-list').each(function () {
                $(this).find('li').each(function () {
                    const current = $(this);
                    const input = current.find('input');
                    if (input.prop('checked')) {
                        const key = current.attr("data-key");
                        savedKeys.push(key)
                    }
                });
            });

            setCookie('index-{{$locale}}-trans-selecteds', savedIds.join(','))
            setCookie('index-{{$locale}}-vocable-selecteds', savedKeys.join(','))
            location.reload();
        }

        const openShareModal = (verse_id, verse_name) => {
            const itemId = `verse-{{$thisChapter->chapter_id}}-${verse_id}`;
            const text = $('.ayath-content').text();
            const ayath = `${text} (${verse_name})`
            $('#shared-text').html(ayath);
            $('#modal1').modal();
            $('#facebookShare').attr("href", `https://www.facebook.com/sharer/sharer.php?u={{$thisUrl}}&quote=${ayath}`)
            $('#twitterShare').attr("href", `https://twitter.com/intent/tweet?text=${ayath}`)
            $('#messengerShare').attr("href", `https://www.facebook.com/dialog/send?link={{$thisUrl}}`)
            $('#whatsappShare').attr("href", `https://web.whatsapp.com/send?text=${ayath}`)
            $('#telegramShare').attr("href", `https://telegram.me/share/?url={{$thisUrl}}&text=${ayath}`)
            $('#redditShare').attr("href", `https://www.reddit.com/submit?url={{$thisUrl}}`)
            $('#lineShare').attr("href", `https://social-plugins.line.me/lineit/share?url={{$thisUrl}}&text=${ayath}`)
            $('#emailShare').attr("href", `mailto:&subject=${verse_name}&body=${ayath}`)
            $(document).ready(function () {
                $('#modal1').modal('open');
            });
        }

        const closeShareModal = () => {
            $('#modal1').modal('close');
        }

        const copyText = (verse_id, verse_name) => {
            const itemId = `verse-{{$thisChapter->chapter_id}}-${verse_id}`;
            const text = $('.ayath-content').text() + ` (${verse_name})`;
            navigator.clipboard.writeText(text);
            M.toast({html: `"${verse_name}" {{{__('app.copied')}}}`})
        }


    </script>

    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/data-tables.min.js') }}"></script>
@endsection
