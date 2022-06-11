@extends('layout.site')
@section('title', __('app.chapter_trans_read', ['chapter_id'=>$thisChapter->chapter_id, 'chapter' => $thisChapter->chapter_name, 'trans' => $thisTranslation->translator]).' | '.$meta['name'])
@section('keywords', $meta['keywords'])
@section('description', $meta['description'])
@section('name', $meta['name'])
@section('author', $meta['author'])
@section('twitter', $meta['twitter'])
@section('locale', $locale)
@section('url', $thisUrl)



@section('content')

{{--    <div class="content-wrapper-before blue-grey lighten-5"></div>--}}
    <div class="col s12 options">
        <div class="col s12 m6">
            <h4 style="margin: 15px 0 0 5px;">{{__('app.chapter_name', ['chapter_id'=>$thisChapter->chapter_id, 'chapter' => $thisChapter->chapter_name])}}</h4>
        </div>
        <div class="col s12 m6">
            <div class="option-show-buttons">
                <button onclick="optionsButton()" class="btn waves-effect waves-light operation-btn">
                    {{ __('app.option') }}
                    <i class="material-icons right">settings</i>
                </button>
                <button onclick="infoButton()" class="btn waves-effect waves-light operation-btn">
                    {{ __('app.about') }}
                    <i class="material-icons right">info</i>
                </button>
            </div>
        </div>
    </div>
    <div style="display:none" id="surah-options" class="col s12 verse-options">
        <div class="input-field col s12 m6 l3">
            <select id="translation">
                @foreach ($translations as $translation)
                    @if($thisTranslation->key == $translation->key)
                        <option selected
                                value="{{ $translation->key }}">{{ __('transl.'.$translation->lang_name) .' - '.$translation->translator }}</option>
                    @else
                        <option
                            value="{{ $translation->key }}">{{ __('transl.'.$translation->lang_name) .' - '.$translation->translator }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="input-field col s12 m6 l3">
            <select id="chapter">
                @foreach ($chaptersBySurah as $chapter)
                    @if($thisChapter->chapter_id == $chapter->chapter_id)
                        <option selected
                                value="{{str_slug($chapter->chapter_name).'_'.$chapter->chapter_id}}">{{$chapter->chapter_id.'. '.$chapter->chapter_name}}</option>
                    @else
                        <option
                            value="{{str_slug($chapter->chapter_name).'_'.$chapter->chapter_id}}">{{$chapter->chapter_id.'. '.$chapter->chapter_name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="input-field col s12 m6 l3">
            <input placeholder="{{__('app.choose_ayath_number')}}" id="verseNumber" type="number" min="0">
        </div>
        <div class="input-field col s12 m6 l3">
            <button id="applyOptions" class="btn verse-option-button waves-effect waves-light" type="submit" name="action">
                {{__('app.apply')}}
            </button>
            <!-- <input placeholder="Aranacak Kelime" id="first_name" type="text"> -->
        </div>

    </div>
    <div style="display:none" id="surah-info" class="col s12 verse-options">
        <div class="col s12">
            <table>
                <thead>
                <tr>
                    <th class="non-mobile">{{ __('app.row') }}</th>
                    <th class="non-mobile">{{ __('app.name') }}</th>
                    <th>{{ __('app.means') }}</th>
                    <th>{{ __('app.arabic') }}</th>
                    <th>{{ __('app.descent_place') }}</th>
                    <th>{{ __('app.descent_row') }}</th>
                    <th>{{ __('app.ayath_count') }}</th>
                    <th class="non-mobile">{{ __('app.word_count') }}</th>
                    <th class="non-mobile">{{ __('app.letter_count') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="non-mobile">{{ $thisChapter->chapter_id }}</td>
                    <td class="non-mobile">{{ $thisChapter->chapter_name }}</td>
                    <td>{{ $thisChapter->chapter_mean }}</td>
                    <td>{{ $thisChapter->chapter_arabic}}</td>
                    <td>{{ __('app.'.$thisChapter->chapter_type) }}</td>
                    <td>{{ $thisChapter->descent_id }}</td>
                    <td>{{ $thisChapter->verse_count }}</td>
                    <td class="non-mobile">{{ $thisChapter->word_qty }}</td>
                    <td class="non-mobile">{{ $thisChapter->letter_qty }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col s12 verse-list">
        <div class="container">
            <div class="section mobile-section">
                <ul class="collapsible">
                    @foreach ($verses as $verse)
                        <li>
                            <x-verse-card
                                chapterId="{{$thisChapter->chapter_id}}"
                                verseId="{{$verse->verse_id}}"
                                verseText="{{$verse->verse_text}}"
                                chapterName="{{$thisChapter->chapter_name}}"
                                compareUrl="{{$compareSlug.$verse->verse_id.$compareSlugEnd}}"
                            />
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>

    <x-modal/>


@endsection


@section('footer')

    <script type="text/javascript">


        $(document).ready(function () {

            $("#applyOptions").click(function(){
                const translation = $('#translation').val();
                const chapter = $('#chapter').val();
                let verseNumber = $('#verseNumber').val();
                const chapterArray = chapter.split("_");

                if (verseNumber !== '') {
                    verseNumber = `#verse-${verseNumber}`
                }
                const url = `{{$url.__('slug.chapter')}}/${chapterArray[0]}/${chapterArray[1]}${verseNumber}`
                setCookie('index-selected-translation-{{$locale}}', translation);
                window.location.href = url;
            });

            if (window.innerWidth < 976) {
                $('.non-mobile').hide();
            }
            // $('#surah-info').removeClass('hide');

            const bookmarkOldId = getCookie("bookmark");
            if (bookmarkOldId) {
                $(`#${bookmarkOldId}`).addClass('selected-bookmark');
            }
        });

        const optionsButton = () => {
            $('#surah-options').slideToggle('slow');
        }

        const infoButton = () => {
            $('#surah-info').slideToggle('slow');
        }

        const bookmark = (verse_id) => {
            const bookmarkId = `verse-${verse_id}`;
            $(`#${bookmarkId}`).addClass('selected-bookmark');
            const bookmarkOldId = getCookie("bookmark");
            if (bookmarkOldId) {
                $(`#${bookmarkOldId}`).removeClass('selected-bookmark');
            }
            if (bookmarkOldId === bookmarkId) {
                setCookie("bookmark", undefined);
            } else {
                setCookie("bookmark", bookmarkId);
            }
        }


        const openShareModal = (verse_id, verse_name) => {
            const itemId = `verse-${verse_id}`;
            const text = $(`#${itemId}`).find('.ayath-content').text();
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
            const itemId = `verse-${verse_id}`;
            const text = $(`#${itemId}`).find('.ayath-content').text() + ` (${verse_name})`;
            navigator.clipboard.writeText(text);
            M.toast({html: `"${verse_name}" {{{__('app.copied')}}}`})
        }


    </script>
@endsection
