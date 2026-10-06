import AppKit
import Foundation
import Vision

guard CommandLine.arguments.count == 2 else {
    fputs("Usage: swift ocr_privacy_sweep.swift <image-directory>\n", stderr)
    exit(2)
}

let root = URL(fileURLWithPath: CommandLine.arguments[1], isDirectory: true)
let manager = FileManager.default
let files = try manager.contentsOfDirectory(
    at: root,
    includingPropertiesForKeys: nil,
    options: [.skipsHiddenFiles]
).filter { $0.pathExtension.lowercased() == "png" }
 .sorted { $0.lastPathComponent < $1.lastPathComponent }

let literalNeedles = [
    "5plc.local",
    "http://",
    "https://",
    "www.youtube",
    "tai_lieu_editor",
    "editor-tai-lieu",
    "hi admin",
    "xin chào, admin",
]
let emailPattern = try NSRegularExpression(
    pattern: #"\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b"#,
    options: [.caseInsensitive]
)

var flagged = 0
var recognizedCharacters = 0
for file in files {
    guard
        let image = NSImage(contentsOf: file),
        let data = image.tiffRepresentation,
        let bitmap = NSBitmapImageRep(data: data),
        let cgImage = bitmap.cgImage
    else {
        fputs("Could not decode \(file.path)\n", stderr)
        continue
    }

    let request = VNRecognizeTextRequest()
    request.recognitionLevel = .accurate
    request.usesLanguageCorrection = true
    request.recognitionLanguages = ["en-US"]
    let handler = VNImageRequestHandler(cgImage: cgImage, options: [:])
    do {
        try handler.perform([request])
    } catch {
        fputs("OCR failed for \(file.lastPathComponent): \(error)\n", stderr)
        continue
    }

    let lines = (request.results ?? []).compactMap { $0.topCandidates(1).first?.string }
    let fullText = lines.joined(separator: "\n")
    recognizedCharacters += fullText.count
    let lowerText = fullText.lowercased()
    let range = NSRange(fullText.startIndex..<fullText.endIndex, in: fullText)
    let hasEmail = emailPattern.firstMatch(in: fullText, options: [], range: range) != nil
    let matchedNeedles = literalNeedles.filter { lowerText.contains($0) }

    if hasEmail || !matchedNeedles.isEmpty {
        flagged += 1
        print(file.lastPathComponent)
        for line in lines {
            let lowerLine = line.lowercased()
            let lineRange = NSRange(line.startIndex..<line.endIndex, in: line)
            if literalNeedles.contains(where: { lowerLine.contains($0) })
                || emailPattern.firstMatch(in: line, options: [], range: lineRange) != nil
            {
                print("  \(line)")
            }
        }
    }
}

print("Scanned \(files.count) PNG files; recognized \(recognizedCharacters) characters; flagged \(flagged).")
if recognizedCharacters == 0 {
    exit(1)
}
