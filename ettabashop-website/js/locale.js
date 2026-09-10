document.getElementById('custom_translate_dropdown').addEventListener('change', function () {
    var lang = this.value;
    if (lang) {
      var frame = document.querySelector('iframe.goog-te-menu-frame');
      if (frame) {
        // Wait for iframe to load
        const frameDoc = frame.contentDocument || frame.contentWindow.document;
        const langAnchor = frameDoc.querySelector(`.goog-te-menu2-item span.text:contains(${lang})`);

        if (langAnchor) {
          langAnchor.click();
        } else {
          // fallback: use cookie trick
          setCookie('googtrans', `/en/${lang}`, 1);
          location.reload();
        }
      } else {
        setCookie('googtrans', `/en/${lang}`, 1);
        location.reload();
      }
    }
  });

  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days*24*60*60*1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }