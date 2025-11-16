CREATE TABLE measurements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    standard_size VARCHAR(10),
    neck DECIMAL(5,2),
    shoulders DECIMAL(5,2),
    bicep DECIMAL(5,2),
    wrist DECIMAL(5,2),
    chest DECIMAL(5,2),
    sleeves DECIMAL(5,2),
    stomach DECIMAL(5,2),
    jacket_length DECIMAL(5,2),
    waist DECIMAL(5,2),
    hips DECIMAL(5,2),
    thigh DECIMAL(5,2),
    knee DECIMAL(5,2),
    inseam_length DECIMAL(5,2),
    pants_length DECIMAL(5,2)
);
