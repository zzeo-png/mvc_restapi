<h3>Seznam vseh oglasov</h3>
<a href="?controller=ads&action=create"><button>Dodaj</button></a>
<table>
  <thead>
    <tr>
      <th>Naslov</th>
      <th>Opis</th>
      <th>Objavil</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <!-- tukaj se sprehodimo čez array oglasov in izpisujemo vrstico posameznega oglasa-->
    <?php foreach ($ads as $ad) { ?>
      <tr>
        <td><?php echo $ad->title; ?></td>
        <td><?php echo $ad->description; ?></td>
        <td><?php echo $ad->user->username; ?></td>
        <td>
          <!-- pri vsakem oglasu dodamo povezavo na akcije show, edit in delete, z idjem oglasa. Uporabnik lahko tako proži novo akcijo s pritiskom na gumb.-->
          <a href='?controller=ads&action=show&id=<?php echo $ad->id; ?>'><button>Prikaži</button></a>
          <a href='?controller=ads&action=edit&id=<?php echo $ad->id; ?>'><button>Uredi</button></a>
          <a href='?controller=ads&action=delete&id=<?php echo $ad->id; ?>'><button>Izbriši</button></a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>