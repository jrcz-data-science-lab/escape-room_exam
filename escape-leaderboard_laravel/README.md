# Escape Room Leaderboard

**Een moderne, veilige webapplicatie voor escape room score management**

---

## Over het Project

De Escape Room Leaderboard is een professionele webapplicatie ontwikkeld met Laravel 12.x. Het systeem centraliseert escape room scores en biedt zowel een publiek leaderboard voor spelers als een beveiligd admin panel voor beheerders.

### Toepassingen

- **Escape room bedrijven** die scores willen beheren en tonen
- **Educatieve doeleinden** als portfolio project voor afstuderen
- **Technische demonstratie** van moderne web development practices
- **Mobiel-vriendelijke** score weergave voor spelers

---

## Features

### Core Functionaliteit

- **📊 Publiek Leaderboard** - Real-time score weergave per game
- **🔍 Game Specifieke Pagina's** - Gedetailleerde leaderboards per escape room
- **🔐 Beveiligd Admin Panel** - Complete game en score management
- **🔑 Per-Game API Tokens** - Veilige score submissions met unieke tokens
- **📱 Responsive Design** - Optimaal op desktop, tablet en mobiel

### Security Features

- **🛡️ Multi-Layer Authentication** - Laravel Auth met admin middleware
- **🔒 API Token Isolation** - Unieke 40-character tokens per escape room
- **📝 IP Logging** - Audit trails voor alle score submissions
- **🛡️ CSRF Protection** - Bescherming tegen cross-site attacks
- **✅ Input Validation** - Comprehensive data validation met Laravel rules
- **👁️ Password Masking** - Extra bescherming tegen shoulder surfing

### User Experience

- **🎨 Modern Dark Theme** - Glassmorphism design met professionele uitstraling
- **📱 Mobile-First Approach** - Geoptimaliseerd voor mobiele apparaten
- **🧭 Intuitive Navigation** - Logische user flow en interface
- **⚡ Real-time Feedback** - Directe bevestiging van acties

---

## Technische Architectuur

### Technology Stack

```
Frontend:    Blade Templates + Custom CSS (Glassmorphism)
Backend:     Laravel 12.x + PHP 8.2+
Database:     MySQL 8.0+ met Eloquent ORM
Build Tools:  Vite voor asset compilation en minification
Security:     Laravel Auth + Custom AdminAuth middleware
```

### MVC Structuur

```
Models:       Game, Score, User
Views:        Admin & Public templates met Blade
Controllers:  GameAdminController, ScoreAdminController, LeaderboardController
Middleware:   AdminAuth, API Token validation
```

### Database Schema

```
games (id, name, slug, description, api_token, created_at, updated_at)
  ↓ 1-to-N relationship (Cascade delete)
scores (id, player_name, score, game_id, submitted_from_ip, created_at, updated_at)
```

---

## Installatie

### Vereisten

- **PHP 8.2+** - Met required extensions
- **Laravel 12.x** - Framework versie
- **MySQL 8.0+** - Database server
- **Node.js 18+** - Voor Vite build process
- **Composer** - PHP package manager
- **Git** - Version control

### Installatie Stappen

```bash
# 1. Clone de repository
git clone https://github.com/jrcz-data-science-lab/escape-room_exam.git
cd escape-leaderboard_laravel

# 2. Installeer PHP dependencies
composer install --optimize-autoloader

# 3. Installeer Node.js dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Configureer database in .env
# Pas DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD aan

# 6. Database setup
php artisan migrate
php artisan db:seed

# 7. Build en compile assets
npm run build

# 8. Start de applicatie
php artisan serve --host=0.0.0.0 --port=8000
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
DB_PASSWORD=jouw_wachtwoord

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Gebruik

### Admin Panel

1. **Navigeer** naar `http://localhost:8000/admin/login`
2. **Login** met admin credentials (geconfigureerd in seeder)
3. **Beheer games** via admin interface
4. **Voeg scores toe** met game-specifieke API tokens
5. **Genereer nieuwe tokens** voor nieuwe games

### Score Submission Process

Via het admin panel kunnen scores worden toegevoegd:

**Velden:**

- `player_name`: Naam van de speler (max 255 characters)
- `score`: Behaalde score (moet ≥ 0 zijn, integer)
- `api_token`: Game-specifieke token voor validatie (40 characters)

**Validatie:**

- Token wordt gevalideerd tegen game's unieke token
- IP adres wordt gelogd voor audit trail
- Success/error feedback wordt direct getoond

### Public Leaderboard

- **Homepage** (`/`) - Overzicht van alle games met score tellingen
- **Game Pagina** (`/games/{slug}`) - Specifieke leaderboards per escape room
- **Search** - Real-time filtering van games op naam
- **Responsive** - Werkt perfect op mobiel, tablet en desktop

---

## Security Details

### Multi-Layer Security Architecture

#### **1. Authentication Layer**

```php
// AdminAuth Middleware
public function handle($request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        return redirect('/admin/login');
    }
    return $next($request);
}
```

#### **2. API Token Security**

```php
// Token Validation in GameAdminController
if ($validated['api_token'] !== $game->api_token) {
    return back()->withErrors(['api_token' => 'Het token is onjuist.']);
}
```

#### **3. Input Validation**

```php
$validated = $request->validate([
    'player_name' => 'required|string|max:255',
    'score' => 'required|integer|min:0',
    'api_token' => 'required|string|size:40',
]);
```

#### **4. Data Protection**

- **IP Logging**: `submitted_from_ip` wordt opgeslagen voor elke score
- **Token Isolation**: Elke game heeft unieke 40-character token
- **CSRF Protection**: @csrf tokens op alle formulieren
- **Password Masking**: Input type="password" met placeholder masking

---

## Performance

### Current Metrics

- **Page Load Time**: ~1-2 seconds
- **Database Queries**: 2-5 per request (geoptimaliseerd)
- **Memory Usage**: ~50MB per request
- **Response Time**: ~200ms voor simple operations

### Optimalisaties

- **Database Indexes**: Op `name`, `slug`, `api_token`, `game_id`
- **Eager Loading**: Voorkomt N+1 problemen met `$game->load('scores')`
- **Asset Minification**: Via Vite build system
- **CSS Optimization**: Glassmorphism effects met efficiënte rendering
- **Query Optimization**: Gebruik van Eloquent relationships

---

## Documentatie

### Complete Documentation Suite

- **[Code Kaart](code_kaart.md)** - Volledige code referentie met uitleg
- **[Code Analyse](code_analyse.md)** - Diepgaande security en performance analyse
- **[Database Uitleg](database_uitleg_examen.md)** - Database documentatie voor examen
- **[Afstudeerportfolio](afstudeerportfolio.md)** - Volledig portfolio documentatie
- **[Bug Reports](bug_report_compact.md)** - Professionele bug rapportage templates

### Technical Documentation

- **[ERD](docs/erd.md)** - Database schema en relaties
- **[Design Document](docs/design.md)** - Architectuur en design keuzes
- **[Cheatsheet](docs/cheatsheet_one_pager_nl.md)** - Snelle referentie voor examen
- **[Release Notes](RELEASE_NOTES.md)** - Complete release v2.0.0 documentatie
- **[Changelog](CHANGELOG.md)** - Gedetailleerde versie historie

---

## API Endpoints

### Public Endpoints

```
GET  /                    - Homepage met games overzicht
GET  /games/{slug}        - Specifieke game leaderboard
```

### Admin Endpoints (Beveiligd)

```
GET  /admin               - Admin dashboard
GET  /admin/login          - Login pagina
POST /admin/logout         - Logout functionaliteit

GET  /admin/games         - Games beheer
GET  /admin/games/create   - Nieuwe game formulier
POST /admin/games         - Game aanmaken
POST /admin/games/{id}/scores - Score toevoegen
DELETE /admin/games/{id}  - Game verwijderen (cascade delete)

GET  /admin/scores        - Scores beheer
GET  /admin/scores/{id}/edit - Score bewerken
PUT  /admin/scores/{id}  - Score update
DELETE /admin/scores/{id}  - Score verwijderen
```

---

## Testing

### Test Coverage

- **Unit Tests**: Model relationships en business logic
- **Feature Tests**: User flows en API endpoints
- **Security Tests**: Authentication en token validation
- **Performance Tests**: Response times en database queries

### Test Commands

```bash
# Run alle tests
php artisan test

# Run specifieke test
php artisan test --filter GameTest

# Generate test coverage
php artisan test --coverage
```

---

## Bug Reports

### Bug Report Process

1. **Check** bestaande [Issues](https://github.com/jrcz-data-science-lab/escape-room_exam/issues)
2. **Create** nieuw issue met duidelijke beschrijving
3. **Provide** gedetailleerde steps to reproduce
4. **Include** environment details en logs

### Bug Report Template

```markdown
## Bug Description

### Steps to Reproduce

1. Ga naar [URL]
2. Klik op [element]
3. Vul in [data]
4. Klik op [button]

### Expected Behavior

[Beschrijf wat je verwachtte te gebeuren]

### Actual Behavior

[Beschrijf wat er daadwerkelijk gebeurde]

### Environment

- PHP Version: [bijv. 8.2.15]
- Laravel Version: [bijv. 12.0.0]
- Database: [bijv. MySQL 8.0]
- Browser: [bijv. Chrome 120.0]
- Operating System: [bijv. Windows 11]

### Additional Info

[Eventuele screenshots, logs of extra informatie]
```

---

## Contributie

### Contributie Guidelines

1. **Fork** de repository naar je eigen GitHub account
2. **Create** feature branch (`git checkout -b feature/amazing-feature`)
3. **Make** je changes met duidelijke commit messages
4. **Test** je changes grondig
5. **Push** to branch (`git push origin feature/amazing-feature`)
6. **Create** Pull Request met gedetailleerde beschrijving

### Development Standards

- **Follow** PSR-12 coding standards
- **Add** tests voor nieuwe features
- **Update** documentation voor changes
- **Use** descriptive commit messages (Conventional Commits)
- **Ensure** code coverage blijft hoog

### Code Style

```bash
# PHP Code Style fixing
./vendor/bin/pint

# Run tests
php artisan test

# Check code coverage
php artisan test --coverage --min=80
```

---

## Deployment

### Production Deployment

#### **Server Requirements**

- **Web Server**: Nginx of Apache met PHP-FPM
- **PHP**: 8.2+ met required extensions
- **Database**: MySQL 8.0+ of MariaDB 10.3+
- **SSL Certificate**: HTTPS required voor production
- **Memory**: Minimum 2GB RAM
- **Storage**: Minimum 20GB SSD

#### **Deployment Steps**

```bash
# 1. Clone repository in production
git clone https://github.com/jrcz-data-science-lab/escape-room_exam.git /var/www/leaderboard

# 2. Install dependencies
cd /var/www/leaderboard
composer install --no-dev --optimize-autoloader
npm install --production

# 3. Environment setup
cp .env.example .env
php artisan key:generate --force

# 4. Configureer production settings
# Set APP_ENV=production, APP_DEBUG=false

# 5. Database setup
php artisan migrate --force

# 6. Build assets
npm run build

# 7. Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

# 8. Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Monitoring

### Performance Monitoring

- **Response Time Monitoring** - Via application logs
- **Database Query Analysis** - Slow query detection
- **Memory Usage Tracking** - PHP memory limits
- **Error Logging** - Comprehensive error tracking

### Security Monitoring

- **Failed Login Attempts** - Brute force detection
- **Invalid Token Usage** - API abuse monitoring
- **IP Address Tracking** - Geographic analysis
- **CSRF Token Validation** - Protection verification

---

## License

Dit project valt onder de **MIT License**.

### MIT License Summary

- ✅ **Commercial use** - Mag commercieel gebruikt worden
- ✅ **Modification** - Mag aangepast worden
- ✅ **Distribution** - Mag gedistributeerd worden
- ✅ **Private use** - Mag privé gebruikt worden
- ❗ **Liability** - Geen garantie of aansprakelijkheid
- ❗ **Copyright** - License en copyright notice behouden

Zie [LICENSE](LICENSE) file voor volledige tekst.

---

## Contact & Support

### GitHub Repository

- **Main Repository**: https://github.com/jrcz-data-science-lab/escape-room_exam
- **Issues**: [Report bugs en problems](https://github.com/jrcz-data-science-lab/escape-room_exam/issues)
- **Discussions**: [Feature requests en vragen](https://github.com/jrcz-data-science-lab/escape-room_exam/discussions)
- **Releases**: [Download latest versions](https://github.com/jrcz-data-science-lab/escape-room_exam/releases)

### Documentation

- **Complete Docs**: [Documentation overview](#documentatie)
- **API Reference**: [API endpoints](#api-endpoints)
- **Security Guide**: [Security details](#security-details)
- **Deployment Guide**: [Production setup](#deployment)

### Community

- **Contributors**: Alle bijdragers worden gewaardeerd
- **Support**: Vragen via GitHub Discussions
- **Feedback**: Altijd welkom voor verbeteringen

---

## Version History

### Current Version: v2.0.0 (Production Release)

- **Release Date**: March 25, 2026
- **Major Features**: Complete functionality, enhanced security, professional UI
- **Performance**: Optimized database queries, asset minification
- **Security**: Multi-layer authentication, API token isolation
- **Documentation**: Production-ready documentation

### Previous Versions

- **v1.2.0** - Error handling improvements
- **v1.1.0** - API token validation
- **v1.0.0** - Initial release


**🚀 Ready for production deployment and academic examination!**
