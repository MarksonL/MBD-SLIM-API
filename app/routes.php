<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    // get
    $app->get('/hotel', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call ReadHotel()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/kamar', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call ReadKamar()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->get('/pelanggan', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call ReadPelanggan()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->get('/reservasi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call ReadReservasi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });  

    // get by id
    $app->get('/hotel/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM hotel WHERE id=?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/kamar/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM kamar WHERE id=?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/pelanggan/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM pelanggan WHERE id=?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/reservasi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM reservasi WHERE id=?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    // post data
    $app->post('/hotel', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        // Pastikan variabel sesuai dengan struktur tabel 'hotel'
        $nama_hotel = $parsedBody["nama_hotel"];
        $alamat = $parsedBody["alamat"];
        $kota = $parsedBody["kota"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('INSERT INTO hotel (nama_hotel, alamat, kota) values (?, ?, ?)');
            $query->execute([$nama_hotel, $alamat, $kota]);
    
            $lastId = $db->lastInsertId();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Hotel disimpan dengan id ' . $lastId
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam menyimpan data hotel: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    $app->post('/kamar', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idHotel = $parsedBody["id_hotel"];
        $nomorKamar = $parsedBody["nomor_kamar"];
        $tipeKamar = $parsedBody["tipe_kamar"];
        $harga = $parsedBody["hargapermalam"];
        $ketersediaan = $parsedBody["ketersediaan_kamar"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('INSERT INTO kamar (id_hotel, nomor_kamar, tipe_kamar, hargapermalam, ketersediaan_kamar) values (?, ?, ?, ?, ?)');
            $query->execute([$idHotel, $nomorKamar, $tipeKamar, $harga, $ketersediaan]);
    
            $lastId = $db->lastInsertId();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Kamar disimpan dengan id ' . $lastId
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam menyimpan data: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });

    $app->post('/pelanggan', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $nama_pelanggan = $parsedBody["nama_pelanggan"];
        $email = $parsedBody["email"];
        $telepon = $parsedBody["telepon"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('INSERT INTO pelanggan (nama_pelanggan, email, telepon) values (?, ?, ?)');
            $query->execute([$nama_pelanggan, $email, $telepon]);
    
            $lastId = $db->lastInsertId();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pelanggan disimpan dengan id ' . $lastId
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam menyimpan data pelanggan: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    $app->post('/reservasi', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $id_pelanggan = $parsedBody["id_pelanggan"];
        $id_kamar = $parsedBody["id_kamar"];
        $tanggal_masuk = $parsedBody["tanggal_masuk"];
        $tanggal_keluar = $parsedBody["tanggal_keluar"];
        $jumlah_orang = $parsedBody["jumlah_orang"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('INSERT INTO reservasi (id_pelanggan, id_kamar, tanggal_masuk, tanggal_keluar, jumlah_orang) values (?, ?, ?, ?, ?)');
            $query->execute([$id_pelanggan, $id_kamar, $tanggal_masuk, $tanggal_keluar, $jumlah_orang]);
    
            $lastId = $db->lastInsertId();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Reservasi disimpan dengan id ' . $lastId
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam menyimpan data reservasi: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    // put data
    $app->put('/hotel/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $currentId = $args['id'];
        $nama_hotel = $parsedBody["nama_hotel"]; // Sesuaikan dengan kolom yang benar
        $alamat = $parsedBody["alamat"]; // Sesuaikan dengan kolom yang benar
        $kota = $parsedBody["kota"]; // Sesuaikan dengan kolom yang benar
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('UPDATE hotel SET nama_hotel = ?, alamat = ?, kota = ? WHERE id = ?'); // Sesuaikan dengan tabel yang benar
            $query->execute([$nama_hotel, $alamat, $kota, $currentId]);
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Hotel dengan id ' . $currentId . ' telah diperbarui'
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam memperbarui data hotel: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    $app->put('/kamar/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $currentId = $args['id'];
        $id_hotel = $parsedBody["id_hotel"]; // Sesuaikan dengan kolom yang benar
        $nomor_kamar = $parsedBody["nomor_kamar"]; // Sesuaikan dengan kolom yang benar
        $tipe_kamar = $parsedBody["tipe_kamar"]; // Sesuaikan dengan kolom yang benar
        $hargapermalam = $parsedBody["hargapermalam"]; // Sesuaikan dengan kolom yang benar
        $ketersediaan_kamar = $parsedBody["ketersediaan_kamar"]; // Sesuaikan dengan kolom yang benar
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('UPDATE kamar SET id_hotel = ?, nomor_kamar = ?, tipe_kamar = ?, hargapermalam = ?, ketersediaan_kamar = ? WHERE id = ?'); // Sesuaikan dengan tabel yang benar
            $query->execute([$id_hotel, $nomor_kamar, $tipe_kamar, $hargapermalam, $ketersediaan_kamar, $currentId]);
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Kamar dengan id ' . $currentId . ' telah diperbarui'
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam memperbarui data kamar: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    $app->put('/pelanggan/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $currentId = $args['id'];
        $nama_pelanggan = $parsedBody["nama_pelanggan"]; // Sesuaikan dengan kolom yang benar
        $email = $parsedBody["email"]; // Sesuaikan dengan kolom yang benar
        $telepon = $parsedBody["telepon"]; // Sesuaikan dengan kolom yang benar
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('UPDATE pelanggan SET nama_pelanggan = ?, email = ?, telepon = ? WHERE id = ?'); // Sesuaikan dengan tabel yang benar
            $query->execute([$nama_pelanggan, $email, $telepon, $currentId]);
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pelanggan dengan ID ' . $currentId . ' telah diperbarui'
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam memperbarui data pelanggan: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    $app->put('/reservasi/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
    
        $currentId = $args['id'];
        $id_pelanggan = $parsedBody["id_pelanggan"]; // Sesuaikan dengan kolom yang benar
        $id_kamar = $parsedBody["id_kamar"]; // Sesuaikan dengan kolom yang benar
        $tanggal_masuk = $parsedBody["tanggal_masuk"]; // Sesuaikan dengan kolom yang benar
        $tanggal_keluar = $parsedBody["tanggal_keluar"]; // Sesuaikan dengan kolom yang benar
        $jumlah_orang = $parsedBody["jumlah_orang"]; // Sesuaikan dengan kolom yang benar
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('UPDATE reservasi SET id_pelanggan = ?, id_kamar = ?, tanggal_masuk = ?, tanggal_keluar = ?, jumlah_orang = ? WHERE id = ?'); // Sesuaikan dengan tabel yang benar
            $query->execute([$id_pelanggan, $id_kamar, $tanggal_masuk, $tanggal_keluar, $jumlah_orang, $currentId]);
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Reservasi dengan ID ' . $currentId . ' telah diperbarui'
                ]
            ));
    
            return $response->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            // Tangani pengecualian database
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Terjadi kesalahan dalam memperbarui data reservasi: ' . $e->getMessage()
                ]
            ));
            return $response;
        }
    });
    

    // delete data
    $app->delete('/hotel/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM hotel WHERE id = ?'); // Gantilah "hotel" dengan nama tabel yang benar
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Hotel dengan ID ' . $currentId . ' dihapus dari database' // Sesuaikan pesan dengan tabel yang benar
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->delete('/kamar/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM kamar WHERE id = ?'); // Gantilah "kamar" dengan nama tabel yang benar
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Kamar dengan ID ' . $currentId . ' dihapus dari database' // Sesuaikan pesan dengan tabel yang benar
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->delete('/pelanggan/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM pelanggan WHERE id = ?'); // Gantilah "pelanggan" dengan nama tabel yang benar
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Pelanggan dengan ID ' . $currentId . ' dihapus dari database' // Sesuaikan pesan dengan tabel yang benar
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    $app->delete('/reservasi/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('DELETE FROM reservasi WHERE id = ?'); // Gantilah "reservasi" dengan nama tabel yang benar
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Reservasi dengan ID ' . $currentId . ' dihapus dari database' // Sesuaikan pesan dengan tabel yang benar
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });    
};
