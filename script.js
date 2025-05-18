let sviFilmovi = [];

fetch('filmovi.csv')
  .then(res => res.text())
  .then(csv => {
    const rezultat = Papa.parse(csv, {
      header: true,
      skipEmptyLines: true
    });

    sviFilmovi = rezultat.data.map(film => ({
      title: film.title,
      year: Number(film.year),
      genre: film.genres,
      duration: Number(film.duration),
      country: film.country?.split(',').map(c => c.trim()) || [],
      avg_vote: Number(film.rating), 
      total_votes: Number(film.total_votes)
    }));

    function prikaziFilmove(filmovi) {
      const tbody = document.querySelector('#filmovi-tablica tbody');
      tbody.innerHTML = '';
      if (filmovi.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 7;
        cell.textContent = 'Nema filmova za odabrane filtre.';
        row.appendChild(cell);
        tbody.appendChild(row);
        return;
      }

      for (const film of filmovi) {
        const row = document.createElement('tr');

        const dodajBtn = document.createElement('button');
        dodajBtn.textContent = 'Dodaj';
    
        dodajBtn.style.textAlign = 'center';
        dodajBtn.addEventListener('click', () => dodajUKosaricu(film));

        const akcijaTd = document.createElement('td');
        akcijaTd.appendChild(dodajBtn);

        row.innerHTML = `
          <td>${film.title}</td>
          <td>${film.year}</td>
          <td>${film.genre}</td>
          <td>${film.duration} min</td>
          <td>${film.country.join(',')}</td>
          <td>${film.total_votes}</td>
        `;
        row.appendChild(akcijaTd);
        tbody.appendChild(row);
      }
    }

    prikaziFilmove(sviFilmovi.slice(0, 10));

    document.getElementById('primijeni-filtere').addEventListener('click', () => {
        const genre = document.getElementById('filter-genre').value;
        const yearFrom = document.getElementById('filter-year-from').value;
        const yearTo = document.getElementById('filter-year-to').value;
        const country = document.getElementById('filter-country').value;
        const votes = document.getElementById('filter-votes').value;
      
        const filtrirani = sviFilmovi.filter(film => {
          const genreMatch = genre === '' || film.genre.includes(genre);
          const yearFromMatch = yearFrom === '' || film.year >= Number(yearFrom);
          const yearToMatch = yearTo === '' || film.year <= Number(yearTo);
          const countryMatch = country === '' || film.country.includes(country);
          const votesMatch = film.total_votes >= Number(votes);
      
          return genreMatch && yearFromMatch && yearToMatch && countryMatch && votesMatch;
        });
      
        prikaziFilmove(filtrirani);
      });
      

      document.getElementById('filter-votes').addEventListener('input', function () {
        document.getElementById('votes-value').textContent = this.value;
      });
  })
  .catch(err => {
    console.error('Greška pri dohvacanju CSV-a:', err);
  });

let kosarica = [];

function dodajUKosaricu(film) {
  if (!kosarica.includes(film)) {
    kosarica.push(film);
    osvjeziKosaricu();
  } else {
    alert("Film je već u košarici!");
  }
}

function osvjeziKosaricu() {
  const lista = document.getElementById('lista-kosarice');
  lista.innerHTML = '';
  kosarica.forEach((film, index) => {
    const li = document.createElement('li');
    li.textContent = film.title;

    const ukloniBtn = document.createElement('button');
    ukloniBtn.textContent = 'Ukloni';
    ukloniBtn.addEventListener('click', () => {
      ukloniIzKosarice(index);
    });

    li.appendChild(ukloniBtn);
    lista.appendChild(li);
  });
}

function ukloniIzKosarice(index) {
  kosarica.splice(index, 1);
  osvjeziKosaricu();
}

document.addEventListener('DOMContentLoaded', () => {
    let sviFilmovi = [];

    const toggle = document.getElementById('toggle-kosarica');
    const sadrzaj = document.getElementById('kosarica-sadrzaj');
  
    toggle.addEventListener('click', () => {
      const isHidden = sadrzaj.style.display === 'none' || sadrzaj.style.display === '';
  
      sadrzaj.style.display = isHidden ? 'block' : 'none';
      toggle.textContent = isHidden ? 'Moja košarica ▲' : 'Moja košarica ▼';
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('potvrdi-kosaricu').addEventListener('click', () => {
        alert('Košarica je potvrđena!');
    });
});



