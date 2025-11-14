export default function handler(req, res) {
  if (req.method === "GET") {
    return res.status(200).json({ message: "GET ALL mahasiswa OK" });
  }

  if (req.method === "POST") {
    return res.status(201).json({ message: "Mahasiswa created" });
  }

  return res.status(405).json({ error: "Method Not Allowed" });
}