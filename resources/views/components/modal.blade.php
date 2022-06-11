<div id="modal1" class="modal">
    <div class="modal-content">
      <h5>{{__('app.share')}}</h5>
      <p id="shared-text">

      </p>
      <div class="share-socials">
        <a id="facebookShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/facebook.png" alt="Facebook" />
        </a>
        <a id="twitterShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/twitter.png" alt="Twitter" />
        </a>
        <a id="messengerShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/messenger.png" alt="Messenger" />
        </a>
        <a id="whatsappShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/whatsapp.png" alt="Whatsapp" />
        </a>
        <a id="telegramShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/telegram.png" alt="Telegram" />
        </a>
        <a id="redditShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/reddit.png" alt="Reddit" />
        </a>
        <a id="lineShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/line.png" alt="Line" />
        </a>
        <a id="emailShare" href="#" target="_blank">
          <img src="{{ config('app.url') }}images/icon/email.png" alt="Email" />
        </a>
      </div>
    </div>
    <div class="modal-footer">
      <button onClick="closeShareModal()"  class="modal-close waves-effect waves-green btn-flat">{{__('app.close')}}</button>
    </div>
  </div>
