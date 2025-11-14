export default function handler(req, res) {
  const { id } = req.query;

  if (req.method === "GET") {
    return res.status(200).json({ message: "GET mahasiswa by ID", id });
  }

  if (req.method === "PUT") {
    return res.status(200).json({ message: "Update mahasiswa", id });
  }

  if (req.method === "DELETE") {
    return res.status(200).json({ message: "Delete mahasiswa", id });
  }

  return res.status(405).json({ error: "Method Not Allowed" });
}