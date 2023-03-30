<!-- pogled za pregeld vseh oglasov-->
<!-- na vrhu damu uporabniku gumb, s katerim proži akcijo create, da lahko dodaja nove uporabnike -->
<h3>Ustvari oglas:</h3>
Naslov: <input type="text" name="title" id="create_ad_title" />
Opis: <input type="text" name="description" id="create_ad_description" />
<button id="create_ad_btn">Dodaj</button>
<hr />
<h3>Seznam vseh oglasov</h3>
<table>
  <thead>
    <tr>
      <th>Naslov</th>
      <th>Opis</th>
      <th>Objavil</th>
      <th></th>
    </tr>
  </thead>
  <tbody id="ads_tbody">
  </tbody>
</table>

<script>
  $(document).ready(async function() {
    await loadAds();
    $("#create_ad_btn").click(createAd);
    $(".edit_ad_btn").click(editClickHandler);
    $(".delete_ad_btn").click(deleteClickHandler);
  });

  async function loadAds() {
    await $.get("/api/index.php/ads", renderAds);
  }

  function renderAds(ads) {
    ads.forEach(function(ad) {
      var row = document.createElement("tr");
      row.id = ad.id;
      row.innerHTML = "<td>" + ad.title + "</td><td>" + ad.description + "</td><td>" + ad.user.username + "</td>";
      row.innerHTML += "<td><button class='edit_ad_btn'>Uredi</button><button class='delete_ad_btn'>Izbriši</button></td>";
      $("#ads_tbody").append(row);
    });
  }

  function createAd() {
    var data = {
      title: $("#create_ad_title").val(),
      description: $("#create_ad_description").val()
    };

    $("#create_ad_title").val("");
    $("#create_ad_description").val("");

    $.post('/api/index.php/ads/', data, function(data) {
      var row = document.createElement("tr");
      row.id = data.id;
      row.innerHTML = "<td>" + data.title + "</td><td>" + data.description + "</td><td>" + data.user.username + "</td>";
      row.innerHTML += "<td><button class='edit_ad_btn'>Uredi</button><button class='delete_ad_btn'>Izbriši</button></td>";
      $(".edit_ad_btn", row).click(editClickHandler);

      $(".delete_ad_btn", row).click(deleteClickHandler);
      $("#ads_tbody").append(row);
    });
  }

  function editClickHandler() {
    var row = $(this).closest("tr");

    if ($(this).text() == "Uredi") {
      $(this).text("Shrani");
      row.find('td:not(:nth-last-child(-n+2)').attr('contenteditable', true);
    } else {
      $(this).text("Uredi");
      row.find('td:not(:nth-last-child(-n+2))').attr('contenteditable', false);
      updateAd(row);
    }
  }

  function updateAd(row) {
    var id = row.attr("id");
    var data = {
      title: row.find('td:nth-child(1)').text(),
      description: row.find('td:nth-child(2)').text()
    };

    $.ajax({
      url: '/api/index.php/ads/' + id,
      method: 'PUT',
      data: JSON.stringify(data),
      contentType: 'application/json'
    });
  }

  function deleteClickHandler() {
    var row = $(this).closest("tr");
    deleteAd(row);
    row.remove();
  }

  function deleteAd(row) {
    var id = row.attr("id");
    $.ajax({
      url: '/api/index.php/ads/' + id,
      method: 'DELETE'
    });
  }
</script>