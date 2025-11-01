# Basit Destek Bileti Sistemi (Laravel)

Bu proje, Laravel Geliştirici Teknik Değerlendirmesi için oluşturulmuş minimal bir Destek Bileti Yönetim Sistemi API'sidir.

Proje, Laravel Sanctum (Authentication), Redis (Caching) ve RabbitMQ (Asynchronous Jobs) kullanarak temel CRUD işlemlerini, yetkilendirmeyi ve performans optimizasyonlarını içermektedir.

---

## 🛠️ Kurulum Adımları

1.  **Projeyi Klonlayın:**

    ```bash
    git clone https://github.com/MertMustehlik/simple-support-ticket-system.git
    cd simple-support-ticket-system
    ```

2.  **Bağımlılıkları Yükleyin:**

    ```bash
    composer install
    ```

3.  **.env Dosyasını Oluşturun:**
    `.env.example` dosyasını kopyalayarak `.env` adında yeni bir dosya oluşturun.

    ```bash
    cp .env.example .env
    ```

4.  **.env Dosyasını Yapılandırın:**
    Oluşturduğunuz `.env` dosyasını açın ve aşağıdaki bölümleri kendi yerel ortamınıza göre doldurun.

    **Veritabanı (Database):**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_ticket_system
    DB_USERNAME=root
    DB_PASSWORD=password
    ```

    **Redis:**

    ```env
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    ```

    **RabbitMQ (Kuyruk):**

    ```env
    QUEUE_CONNECTION=rabbitmq

    RABBITMQ_HOST=127.0.0.1
    RABBITMQ_PORT=5672
    RABBITMQ_USER=guest
    RABBITMQ_PASSWORD=guest
    RABBITMQ_VHOST=/
    ```

5.  **Uygulama Anahtarını Oluşturun:**
    ```bash
    php artisan key:generate
    ```

---

## 🗄️ Veritabanı (Migration & Seed)

1.  **Migration:**
    Veritabanı tablolarını oluşturmak için aşağıdaki komutu çalıştırın.

    ```bash
    php artisan migrate
    ```

2.  **Seed:**
    Örnek kullanıcılar ve destek talepleri oluşturmak için seeder'ı çalıştırabilirsiniz.
    ```bash
    php artisan db:seed
    ```

---

## 🏁 Uygulamayı Çalıştırma

Uygulamayı yerel sunucuda başlatmak için:

```bash
php artisan serve
```

RabbitMQ için ayrı bir terminal açarak:

```bash
php artisan queue:work rabbitmq
```

## 📚 API Uç Noktaları

### Authentication

Register ve Login dışındaki tüm uç noktalar Laravel Sanctum ile korunmaktadır. Bu nedenle, korunan endpoint’lere yapılan isteklerde Authorization: Bearer <token> şeklinde geçerli bir Sanctum erişim tokenı gönderilmelidir.

Örnek:
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6...

##### Register

-   **Endpoint**: `POST /api/register`
-   **Request Body**:
    -   `name`: (string) Kullanıcı adı.
    -   `email`: (string) Kullanıcı e-posta adresi.
    -   `password`: (string) Kullanıcı şifresi.
    -   `password_confirmation`: (string) Kullanıcı şifresi onaylama.
-   **Response**:
    -   `user`: (object)
    -   `token`: (string)

##### Login

-   **Endpoint**: `POST /api/login`
-   **Request Body**:
    -   `email`: (string) Kullanıcı e-posta adresi.
    -   `password`: (string) Kullanıcı şifresi.
-   **Response**:
    -   `user`: (object)
    -   `token`: (string)

---

### Tickets

##### List

-   **Endpoint**: `GET /api/tickets`
-   **Query Params**:
    -   `per_page`: (int, optional) Sayfa başına destek talebi sayısı.
    -   `page`: (int, optional) Sayfa numarası.
-   **Response**:
    -   `data`: (array)
    -   `links`: (object)
    -   `meta`: (object)

##### Store

-   **Endpoint**: `POST /api/tickets`
-   **Request Body**:
    -   `title`: (string) Destek talebi başlığı.
    -   `description`: (string) Destek talebi açıklaması.
-   **Response**:
    -   `message`: (string)
    -   `data`: (object)

##### Show

-   **Endpoint**: `GET /api/tickets/{id}`
-   **Response**:
    -   `data`: (object)

##### Update Status

-   **Endpoint**: `PATCH /api/tickets/{id}/status`
-   **Request Body**:
    -   `status`: (string) Destek talebi durumu.
-   **Response**:
    -   `message`: (string)

---

## 💡 Teknoloji Açıklamaları (Redis & RabbitMQ)

Bu projede, modern web uygulamalarının iki temel ihtiyacı olan hız ve verimlilik için Redis ve RabbitMQ kullanılmıştır.

### Redis (Önbellekleme - Caching)

-   **Amaç:** Sıkça erişilen verileri (bu projede bilet detayları ve listeleri) veritabanı yerine çok daha hızlı olan RAM (hafıza) üzerinde tutmaktır. Bu sayede veritabanı yükü azalır ve API yanıt süreleri ciddi ölçüde kısalır.
-   **Kullanım:**
    -   `GET /api/tickets` (Liste) endpointinden gelen başarılı yanıtlar, **60 saniye** süreyle Redis'te önbelleklenir.
    -   60 saniye içinde aynı istek tekrar gelirse, sistem veritabanına hiç gitmeden veriyi doğrudan Redis'ten sunar.
-   **Önbellek Temizleme (Invalidation):**
    -   Verinin güncel kalması kritiktir. Bu nedenle, kullanıcı yeni bir bilet oluşturduğunda (`POST /api/tickets`) veya mevcut bir biletin durumunu güncellediğinde (`PATCH`), ilgili önbellek (cache) anahtarları otomatik olarak silinir.
    -   Bu sayede kullanıcı, bir değişiklik yaptıktan sonraki ilk `GET` isteğinde daima en güncel veriyi görür.

### RabbitMQ (Asenkron İşlem Kuyruğu)

-   **Amaç:** Kullanıcının beklemesini gerektirmeyen, ancak yapılması gereken "ağır" veya "zaman alıcı" işlemleri (bu projede: loglama) ana işlemden ayırmaktır. Bu, API'nin kullanıcıya anında yanıt vermesini sağlar.
-   **Kullanım (Akış):**
    1.  Kullanıcı bir biletin durumunu `PATCH /api/tickets/{id}/status` endpoint'i ile günceller.
    2.  Sistem, değişikliği anında veritabanındaki `tickets` tablosuna yazar ve kullanıcıya "Başarılı" yanıtını döner (Hızlı yanıt).
    3.  Aynı anda, bu değişikliği loglamak için bir `TicketStatusUpdated` olayı (Event) tetiklenir.
    4.  Bu olayı dinleyen bir 'Listener', "Loglama İşini" (Queued Job) alır ve RabbitMQ kuyruğuna gönderir.
    5.  Arka planda çalışan `php artisan queue:work` komutu (worker) bu işi kuyruktan alır ve `ticket_logs` tablosuna kaydı ekler.
-   **Sonuç:** Kullanıcı, loglama işleminin bitmesini bir saniye bile beklemez. Loglama işlemi (örn: `ticket_logs` tablosu kilitlendiği için) o an başarısız olsa bile, bu durum kullanıcının ana isteğini etkilemez ve işlem kuyrukta yeniden denenmek üzere bekler.
