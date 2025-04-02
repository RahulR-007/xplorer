import { put } from "@vercel/blob";
import { nanoid } from "nanoid";

export const config = {
  api: {
    bodyParser: false,
  },
};

export default async function handler(req, res) {
  if (req.method !== "POST") return res.status(405).end();

  const file = req.body;
  const filename = req.headers["x-filename"];
  const extension = filename.split(".").pop();
  const blobName = `${filename.split(".")[0]}-${nanoid()}.${extension}`;

  const { url } = await put(blobName, file, {
    access: "public",
  });

  res.status(200).json({ url });
}
