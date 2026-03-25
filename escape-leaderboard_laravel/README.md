# Escape Room Leaderboard

**Een moderne webapplicatie voor escape room score management**

---

## Over het Project

De Escape Room Leaderboard is een professionele webapplicatie ontwikkeld met Laravel 12.x. Het systeem centraliseert escape room scores en biedt zowel een publiek leaderboard voor spelers als een beveiligd admin panel voor beheerders.

### Toepassingen

- **Escape room bedrijven** die scores willen beheren en tonen
- **Educatieve doeleinden** als portfolio project
- **Technische demonstratie** van moderne web development
- **Mobiel-vriendelijke** score weergave voor spelers

---

## Features

### Core Functionaliteit

- **Publiek Leaderboard** - Real-time score weergave per game
- **Game Specifieke Pagina's** - Gedetailleerde leaderboards per escape room
- **Beveiligd Admin Panel** - Complete game en score management
- **Per-Game API Tokens** - Veilige score submissions
- **Responsive Design** - Optimaal op desktop, tablet en mobiel

### Security

- **Multi-Layer Authentication** - Laravel Auth met admin middleware
- **API Token Isolation** - Unieke tokens per escape room
- **IP Logging** - Audit trails voor alle score submissions
- **CSRF Protection** - Bescherming tegen cross-site attacks
- **Input Validation** - Comprehensive data validation
- **Password Masking** - Extra bescherming tegen shoulder surfing

### User Experience

- **Modern Dark Theme** - Glassmorphism design met professionele uitstraling
- **Mobile-First Approach** - Geoptimaliseerd voor mobiele apparaten
- **Intuitive Navigation** - Logische user flow en interface
- **Real-time Feedback** - Directe bevestiging van acties

---

## Technische Architectuur

### Stack

- **Frontend**: Blade Templates + Custom CSS
- **Backend**: Laravel 12.x + PHP 8.2+
- **Database**: MySQL 8.0+ met Eloquent ORM
- **Build Tools**: Vite voor asset compilation
- **Security**: Laravel Auth + Custom middleware

### MVC Structuur

```
Models: Game, Score, User
Views: Admin & Public templates
Controllers: GameAdminController, ScoreAdminController, LeaderboardController
Middleware: AdminAuth, API Token validation
```

### Database Schema

```
games (id, name, slug, description, api_token)
  ↓ 1-to-N relationship
scores (id, player_name, score, game_id, submitted_from_ip)
```

---

## Installatie

### Vereisten

- PHP 8.2+
- Laravel 12.x
- MySQL 8.0+
- Node.js 18+ (voor Vite build)
- Composer

### Installatie Stappen

```bash
# 1. Clone de repository
git clone https://github.com/jrcz-data-science-lab/escape-room_exam.git
cd escape-leaderboard_laravel

# 2. Installeer dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Build assets
npm run build

# 6. Start de applicatie
php artisan serve
```

### Environment Configuratie

```env
APP_NAME="Escape Room Leaderboard"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=escape_leaderboard
DB_USERNAME=root
DB_PASSWORD=
```

---

## Gebruik

### Admin Panel

1. Navigeer naar `/admin/login`
2. Login met admin credentials
3. Beheer games en scores via de admin interface
4. Genereer API tokens per game

### Score Submission

Via het admin panel kunnen scores worden toegevoegd met de volgende velden:

- `player_name`: Naam van de speler
- `score`: Behaalde score (moet ≥ 0 zijn)
- `api_token`: Game-specifieke token voor validatie

### Public Leaderboard

- **Homepage**: Overzicht van alle games met score tellingen
- **Game Pagina**: `/games/{slug}` voor specifieke leaderboards
- **Search**: Real-time filtering van games

---

## Documentatie

### Complete Documentation Suite

- **[Code Kaart](code_kaart.md)** - Volledige code referentie en uitleg
- **[Code Analyse](code_analyse.md)** - Diepgaande security en performance analyse
- **[Database Uitleg](database_uitleg_examen.md)** - Database documentatie voor examen
- **[Afstudeerportfolio](afstudeerportfolio.md)** - Volledig portfolio documentatie
- **[Bug Reports](bug_report_compact.md)** - Professionele bug rapportage

### Technical Documentation

- **[ERD](docs/erd.md)** - Database schema en relaties
- **[Design Document](docs/design.md)** - Architectuur en design keuzes
- **[Cheatsheet](docs/cheatsheet_one_pager_nl.md)** - Snelle referentie voor examen
- **[Release Notes](RELEASE_NOTES.md)** - Complete release documentatie

---

## Security Details

### Multi-Layer Security

1. **Authentication Layer** - Laravel Auth met `is_admin` check
2. **Authorization Layer** - AdminAuth middleware protection
3. **API Security** - Per-game unique tokens (40-char cryptographically secure)
4. **Input Validation** - Laravel validation rules op alle endpoints
5. **CSRF Protection** - @csrf tokens op alle formulieren
6. **Data Protection** - IP logging en secure token storage

### Security Implementatie

```php
// AdminAuth Middleware
if (!auth()->check() || !auth()->user()->is_admin) {
    return redirect('/admin/login');
}

// Token Validation
if ($validated['api_token'] !== $game->api_token) {
    return back()->withErrors(['api_token' => 'Het token is onjuist.']);
}
```

---

## Performance

### Huidige Metrics

- **Page Load Time**: ~1-2 seconds
- **Database Queries**: 2-5 per request
- **Memory Usage**: ~50MB per request
- **Response Time**: ~200ms voor simple operations

### Optimalisaties

- **Database Indexes** - Op name, slug, api_token, game_id
- **Eager Loading** - Voorkomt N+1 problemen
- **Asset Minification** - Via Vite build system
- **CSS Optimization** - Glassmorphism effects

---

## Bug Reports

### Bug Report Process

1. Check bestaande [Issues](https://github.com/jrcz-data-science-lab/escape-room_exam/issues)
2. Create nieuw issue met duidelijke beschrijving
3. Provide gedetailleerde steps to reproduce
4. Include environment details

### Bug Report Template

```markdown
## Bug Description

### Steps to Reproduce

1. ...
2. ...
3. ...

### Expected Behavior

### Actual Behavior

### Environment

- PHP Version:
- Laravel Version:
- Browser:
```

---

## Contributie

### Guidelines

1. Fork de repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Create Pull Request

### Development Standards

- Follow PSR-12 coding standards
- Add tests voor nieuwe features
- Update documentation voor changes
- Use descriptive commit messages

---

## License

Dit project valt onder de MIT License. Zie [LICENSE](LICENSE) file voor details.

---

## Contact

- **GitHub Issues**: [Report bugs](https://github.com/jrcz-data-science-lab/escape-room_exam/issues)
- **GitHub Discussions**: [Feature requests](https://github.com/jrcz-data-science-lab/escape-room_exam/discussions)

---

